<?php

namespace App\Exports;

use App\Models\Answare;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllDataExport implements FromCollection, WithHeadings, WithMapping
{
    // Mengambil data dari answare beserta data yang berelasi
    public function collection()
    {
        return Answare::with(['user', 'question', 'category'])->get();
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
            'Category Name',     // Nama kategori dari tabel categories
            'Question Text',     // Pertanyaan dari tabel questions
            'Answer',            // Jawaban dari tabel answare
            'Nilai',             // Nilai dari tabel answare
        ];
    }

    // Mapping data yang akan diekspor
    public function map($answare): array
    {
        return [
            $answare->user->id,          // Mengambil nama user
            $answare->user->age,         // Mengambil umur user
            $answare->user->province,    // Mengambil provinsi user
            $answare->user->city,        // Mengambil kota user
            $answare->user->kelurahan,   // Mengambil kelurahan user
            $answare->user->kecamatan,   // Mengambil kecamatan user
            $answare->user->gender,      // Mengambil jenis kelamin user
            $answare->user->no_hp,       // Mengambil nomor hp user
            $answare->category->name,      // Mengambil nama kategori
            $answare->question->question_text,      // Mengambil teks pertanyaan
            $answare->answer,              // Mengambil jawaban dari tabel answare
            $answare->nilai,               // Mengambil nilai dari tabel answare
        ];
    }
}
