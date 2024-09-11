<?php

namespace App\Http\Controllers;

use App\Exports\AcpAnswerExport;
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
            ->where('selected_answers.category_id', 1)
            ->groupBy('answer')
            ->get();

        $answersSection2 = Answare::select('answer', DB::raw('count(*) as totalSection2'))
            ->join('questions', 'questions.id', '=', 'selected_answers.question_id')
            ->where('questions.section', 2)
            ->where('selected_answers.category_id', 1)
            ->groupBy('answer')
            ->get();

        // Menambahkan query untuk category_id 2 dan section 1
        $answersAcp = Answare::select('answer', DB::raw('count(*) as totalCategory2Section1'))
            ->join('questions', 'questions.id', '=', 'selected_answers.question_id')
            ->where('questions.section', 1)
            ->where('selected_answers.category_id', 2)
            ->groupBy('answer')
            ->get();

        $labels = $answers->pluck('answer');
        $data = $answers->pluck('total');
        $dataSection2 = $answersSection2->pluck('totalSection2');
        $labelsSection2 = $answersSection2->pluck('answer');

        // Menyiapkan data untuk category_id 2 dan section 1
        $dataAcp = $answersAcp->pluck('totalCategory2Section1');
        $labelsAcp = $answersAcp->pluck('answer');

        return view('dashboard.pages.index', compact('labels', 'data', 'labelsSection2', 'dataSection2', 'labelsAcp', 'dataAcp'));
    }
    public function exportExcel()
    {
        return Excel::download(new UserAnswersExport, 'user_answers.xlsx');
    }
    public function AcpExportExcel()
    {
        return Excel::download(new AcpAnswerExport, 'user_answers_acp.xlsx');
    }
}
