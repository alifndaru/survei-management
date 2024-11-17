<?php

namespace App\Exports;

use App\Models\Answare;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllDataExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return User::with(['answers' => function ($query) {
            $query->with(['category', 'question']);
        }])->get();
    }

    public function headings(): array
    {
        return [
            'Users_id',
            'age',
            'province',
            'city',
            'kelurahan',
            'kecamatan',
            'gender',
            'no_hp',
            'Pertanyaan',
            'ACP',
            'Skala Stress',
            'Straus',
            'Nilai'
        ];
    }

    public function map($user): array
    {
        $rows = [];

        // Dapatkan semua jawaban user
        $answers = $user->answers;

        foreach ($answers as $answer) {
            $row = [
                $user->id, // Users_id
                $user->age, // Age
                $user->province, // Province
                $user->city, // City
                $user->kelurahan, // Kelurahan
                $user->kecamatan, // Kecamatan
                $user->gender,
                $user->no_hp,
                $answer->question->question_text ?? '', // Pertanyaan
                '', // ACP
                '', // Skala Stress
                '', // Straus
                $answer->nilai // Nilai
            ];

            // Tempatkan jawaban sesuai kategori
            // Tempatkan jawaban sesuai kategori
            switch ($answer->category_id) {
                case 1: // ACP
                    $row[9] = $answer->answer; // Correct index for ACP
                    break;
                case 2: // Skala Stress
                    $row[10] = $answer->answer; // Correct index for Skala Stress
                    break;
                case 3: // Straus
                    $row[11] = $answer->answer; // Correct index for Straus
                    break;
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
