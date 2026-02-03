<?php

namespace App\Imports;

use App\Models\KategoriBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KategoriBarangImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Coba berbagai kemungkinan nama kolom
        $namaKategori = $row['nama_kategori'] ?? $row['nama'] ?? $row['namakategori'] ?? null;
        
        // Skip jika nama kategori kosong atau hanya whitespace
        if (empty($namaKategori) || trim($namaKategori) == '') {
            return null;
        }

        $namaKategori = trim($namaKategori);

        // Cek apakah kategori sudah ada
        $exists = KategoriBarang::where('nama', $namaKategori)->exists();
        
        if ($exists) {
            // Skip jika sudah ada
            return null;
        }

        return new KategoriBarang([
            'nama' => $namaKategori,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_kategori' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
        ];
    }
}
