<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SkalaStressExport implements FromQuery, WithHeadings, WithStyles, WithEvents
{
    public function query()
    {
        // Query to get users and their answers for multiple questions in both sections
        return User::query()
            ->leftJoin('selected_answers', 'users.id', '=', 'selected_answers.user_id')
            ->leftJoin('questions', 'selected_answers.question_id', '=', 'questions.id')
            ->where('selected_answers.category_id', 3) // Assuming the category_id column is in the selected_answers table
            ->whereIn('questions.section', [1, 2]) // Include both sections 1 and 2
            ->select(
                'users.id as user_id',
                'users.age as age',
                'users.province as province',
                'users.city as city',
                'users.kelurahan as kelurahan',
                'users.kecamatan as kecamatan',
                'users.gender as gender',
            'users.no_hp as no_hp',
                'questions.question_text as question_text',
            'selected_answers.answer as answer',
            'selected_answers.nilai as nilai'
            )
            ->orderBy('users.id') // Sort by user to group answers together
            ->orderBy('questions.id') // Sort by questions
            ->orderBy('selected_answers.id'); // Sort by answer id for section 2 (multiple answers per question)
    }

    public function headings(): array
    {
        return ["User ID", "Age", "Province", "City", "Kelurahan", "Kecamatan", "Gender", "No HP", "Question", "Answer", "Nilai"];
    }

    // Applying styles to merge cells
    public function styles(Worksheet $sheet)
    {
        // Example: Set the font to bold for the first row (headings)
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        return [];
    }

    // Handling events to merge user cells for the same user and handle section 2 questions/answers
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                $data = $worksheet->toArray();

                $currentUserId = null;
                $startRow = 2; // Assuming data starts at row 2
                $questionStartRow = null;
                $currentQuestion = null;

                foreach ($data as $rowIndex => $row) {
                    if ($rowIndex == 0) continue; // Skip header row

                    if ($currentUserId !== $row[0]) {
                        // New user found, merge the previous user's cells if any
                        if ($currentUserId !== null) {
                            // Merge all cells for user data columns A-H
                            $worksheet->mergeCells("A$startRow:A$rowIndex");
                            $worksheet->mergeCells("B$startRow:B$rowIndex");
                            $worksheet->mergeCells("C$startRow:C$rowIndex");
                            $worksheet->mergeCells("D$startRow:D$rowIndex");
                            $worksheet->mergeCells("E$startRow:E$rowIndex");
                            $worksheet->mergeCells("F$startRow:F$rowIndex");
                            $worksheet->mergeCells("G$startRow:G$rowIndex");
                            $worksheet->mergeCells("H$startRow:H$rowIndex");
                        }

                        // Update current user and start a new range for merging
                        $currentUserId = $row[0];
                        $startRow = $rowIndex + 1;
                    }

                    // Merge question column (I) if section 2 and the question is the same
                    if ($row[8] !== $currentQuestion && $row[7] == 2) {
                        // New question found, merge the previous question's cells if any
                        if ($currentQuestion !== null && $questionStartRow !== null) {
                            $worksheet->mergeCells("I$questionStartRow:I" . ($rowIndex));
                        }

                        // Set new question and mark the start row for merging
                        $currentQuestion = $row[8];
                        $questionStartRow = $rowIndex + 1;
                    }
                }

                // Merge the last user's cells
                $worksheet->mergeCells("A$startRow:A" . count($data));
                $worksheet->mergeCells("B$startRow:B" . count($data));
                $worksheet->mergeCells("C$startRow:C" . count($data));
                $worksheet->mergeCells("D$startRow:D" . count($data));
                $worksheet->mergeCells("E$startRow:E" . count($data));
                $worksheet->mergeCells("F$startRow:F" . count($data));
                $worksheet->mergeCells("G$startRow:G" . count($data));
                $worksheet->mergeCells("H$startRow:H" . count($data));

                // Merge the last question's cells
                if ($currentQuestion !== null && $questionStartRow !== null) {
                    $worksheet->mergeCells("I$questionStartRow:I" . count($data));
                }
            }
        ];
    }
}
