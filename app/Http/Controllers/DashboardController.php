<?php

namespace App\Http\Controllers;

use App\Exports\UserAnswersExport;
use App\Models\Answare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $answers = Answare::select('answer', DB::raw('count(*) as total'))
            ->join('questions', 'questions.id', '=', 'selected_answers.question_id')
            ->where('questions.section', 1)
            ->where('selected_answers.category_id', 1) // Assuming the category_id column is in the selected_answers table
            ->groupBy('answer')
            ->get();

        $answersSection2 = Answare::select('answer', DB::raw('count(*) as totalSection2'))
            ->join('questions', 'questions.id', '=', 'selected_answers.question_id')
            ->where('questions.section', 2)
            ->where('selected_answers.category_id', 1) // Assuming the category_id column is in the selected_answers table
            ->groupBy('answer')
            ->get();
        $labels = $answers->pluck('answer');
        $data = $answers->pluck('total');
        $dataSection2 = $answersSection2->pluck('totalSection2');
        $labelsSection2 = $answersSection2->pluck('answer');
        return view('dashboard.pages.index', compact('labels', 'data', 'labelsSection2', 'dataSection2'));
    }

    public function exportExcel()
    {
        return Excel::download(new UserAnswersExport, 'user_answers.xlsx');
    }
}
