<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class BarangImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Coba berbagai kemungkinan nama kolom
        $namaBarang = $row['nama_barang'] ?? $row['nama'] ?? $row['namabarang'] ?? null;
        
        // Skip jika nama barang kosong atau hanya whitespace
        if (empty($namaBarang) || trim($namaBarang) == '') {
            return null;
        }

        $namaBarang = trim($namaBarang);

        // Handle Kategori - cari atau buat baru
        $kategoriId = null;
        $kategori = $row['kategori'] ?? null;
        if (!empty($kategori)) {
            $kategori = KategoriBarang::firstOrCreate(
                ['nama' => trim($kategori)],
                ['keterangan' => 'Auto-created from import']
            );
            $kategoriId = $kategori->id;
        }

        // Map kondisi dari text ke enum
        $kondisi = 'baik';
        if (!empty($row['kondisi'])) {
            $kondisiLower = strtolower(trim($row['kondisi']));
            if (str_contains($kondisiLower, 'rusak ringan')) {
                $kondisi = 'rusak_ringan';
            } elseif (str_contains($kondisiLower, 'rusak berat') || str_contains($kondisiLower, 'rusak parah')) {
                $kondisi = 'rusak_berat';
            }
        }

        // Map jenis dari text ke enum
        $jenis = 'inventaris';
        if (!empty($row['jenis'])) {
            $jenisLower = strtolower(trim($row['jenis']));
            if (str_contains($jenisLower, 'habis pakai') || str_contains($jenisLower, 'consumable')) {
                $jenis = 'habis_pakai';
            }
        }

        // Get jumlah dari import
        $jumlah = !empty($row['jumlah']) ? (int)$row['jumlah'] : 0;

        return new Barang([
            'kategori_barang_id' => $kategoriId,
            'kode' => Barang::generateKode(),
            'nama' => $namaBarang,
            'nama_barang' => $namaBarang,
            'jumlah' => $jumlah,
            'satuan' => !empty($row['satuan']) ? trim($row['satuan']) : 'pcs',
            'jenis' => $jenis,
            'kondisi' => $kondisi,
            'lokasi' => !empty($row['lokasi']) ? trim($row['lokasi']) : null,
            'keterangan' => !empty($row['keterangan']) ? trim($row['keterangan']) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_barang' => 'nullable|string|max:255',
            '*.jumlah' => 'nullable|numeric|min:0',
            '*.satuan' => 'nullable|string|max:50',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh negatif',
        ];
    }
}
