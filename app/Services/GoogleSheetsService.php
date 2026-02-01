<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Psychorobotic App');
        $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $this->client->setAuthConfig(base_path('credentials-sheets.json'));
        $this->client->setAccessType('offline');

        $this->service = new Google_Service_Sheets($this->client);
        $this->spreadsheetId = env('GOOGLE_SHEETS_BARANG_ID');
    }

    /**
     * Get all data from sheet
     */
    public function getSheetData($range = 'Barang!A2:K')
    {
        try {
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            return $response->getValues();
        } catch (\Exception $e) {
            \Log::error('Google Sheets Get Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Append data to sheet
     */
    public function appendRow($values)
    {
        try {
            $range = 'Barang!A:K';
            $body = new Google_Service_Sheets_ValueRange([
                'values' => [$values]
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            
            $result = $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Google Sheets Append Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update specific row
     */
    public function updateRow($rowNumber, $values)
    {
        try {
            $range = "Barang!A{$rowNumber}:K{$rowNumber}";
            $body = new Google_Service_Sheets_ValueRange([
                'values' => [$values]
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            
            $result = $this->service->spreadsheets_values->update(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Google Sheets Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete row by clearing data
     */
    public function deleteRow($rowNumber)
    {
        try {
            $range = "Barang!A{$rowNumber}:K{$rowNumber}";
            $body = new Google_Service_Sheets_ValueRange([
                'values' => [['']]
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            
            $this->service->spreadsheets_values->clear(
                $this->spreadsheetId,
                $range,
                new \Google_Service_Sheets_ClearValuesRequest()
            );
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Google Sheets Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find row number by kode barang
     */
    public function findRowByKode($kode)
    {
        try {
            // Get all data including kode column (B)
            $data = $this->getSheetData('Barang!A2:K');
            
            if (!$data) {
                return null;
            }
            
            foreach ($data as $index => $row) {
                // Kolom B (index 1) adalah kode
                if (isset($row[1]) && $row[1] == $kode) {
                    return $index + 2; // +2 karena index 0 = row 2 (row 1 adalah header)
                }
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Google Sheets Find Row Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync barang data to Google Sheets
     */
    public function syncBarang($barang)
    {
        $values = [
            $barang->id,
            $barang->kode,
            $barang->nama,
            $barang->kategori ? $barang->kategori->nama : '-',
            $barang->jenis == 'inventaris' ? 'Inventaris' : 'Habis Pakai',
            $barang->jumlah,
            $barang->satuan,
            $barang->stok_tersedia,
            $barang->kondisi == 'baik' ? 'Baik' : ($barang->kondisi == 'rusak_ringan' ? 'Rusak Ringan' : 'Rusak Berat'),
            $barang->lokasi ?: '-',
            $barang->keterangan ?: '-',
        ];

        // Cari row berdasarkan kode
        $rowNumber = $this->findRowByKode($barang->kode);

        \Log::info('Google Sheets Sync - Kode: ' . $barang->kode . ', Row Found: ' . ($rowNumber ?: 'null'));

        if ($rowNumber) {
            // Update existing row
            \Log::info('Updating row ' . $rowNumber);
            return $this->updateRow($rowNumber, $values);
        } else {
            // Append new row
            \Log::info('Appending new row');
            return $this->appendRow($values);
        }
    }

    /**
     * Delete barang from Google Sheets
     */
    public function deleteBarang($kode)
    {
        $rowNumber = $this->findRowByKode($kode);
        
        if ($rowNumber) {
            return $this->deleteRow($rowNumber);
        }
        
        return false;
    }
}
