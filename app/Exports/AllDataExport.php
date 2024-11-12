<?php

namespace App\Exports;

use App\Models\Answare;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllDataExport implements FromCollection, WithHeadings, WithMapping
{
    // Mengambil data dari answare beserta relasi yang dibutuhkan
    public function collection()
    {
        return Answare::with(['user', 'category'])->get(); // Pastikan relasi ada untuk user dan category
    }

    // Menambahkan header untuk file Excel
    public function headings(): array
    {
        return [
            'User_Id',         // Nama user dari tabel users
            'Age',
            'Province',
            'City',
            'Kelurahan',
            'Kecamatan',
            'gender',
            'No Hp',
            'ACP',               // ACP value
            'Skala Stress',      // Skala stress
            'Answer',            // Jawaban dari tabel answare
            'Nilai',             // Nilai dari tabel answare
        ];
    }

    // Mapping data yang akan diekspor
    public function map($answare): array
    {
        return [
            $answare->user->name,            // Mengambil nama user
            $answare->user->id,          // Mengambil nama user
            $answare->user->age,         // Mengambil umur user
            $answare->user->province,    // Mengambil provinsi user
            $answare->user->city,        // Mengambil kota user
            $answare->user->kelurahan,   // Mengambil kelurahan user
            $answare->user->kecamatan,   // Mengambil kecamatan user
            $answare->user->gender,      // Mengambil jenis kelamin user
            $answare->user->no_hp,       // Mengambil nomor hp user
            $answare->acp,                   // Mengambil nilai ACP (jika ada di model Answare)
            $answare->skala_stress,          // Mengambil skala stress (jika ada di model Answare)
            $answare->answer,                // Mengambil jawaban
            $answare->nilai,                 // Mengambil nilai
        ];
    }
}
