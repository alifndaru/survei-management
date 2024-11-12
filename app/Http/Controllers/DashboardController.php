<?php

namespace App\Http\Controllers;

use App\Exports\AcpAnswerExport;
use App\Exports\AllDataExport;
use App\Exports\SkalaStressExport;
use App\Exports\UserAnswersExport;
use App\Models\Answare;
use App\Models\Category;
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

        $answersSkalaStress = Answare::select('answer', DB::raw('count(*) as totalCategory3Section1'))
            ->join('questions', 'questions.id', '=', 'selected_answers.question_id')
            ->where('questions.section', 1)
            ->where('selected_answers.category_id', 3)
            ->groupBy('answer')
            ->get();

        $labels = $answers->pluck('answer');
        $data = $answers->pluck('total');
        $dataSection2 = $answersSection2->pluck('totalSection2');
        $labelsSection2 = $answersSection2->pluck('answer');

        // Menyiapkan data untuk category_id 2 dan section 1
        $dataAcp = $answersAcp->pluck('totalCategory2Section1');
        $labelsAcp = $answersAcp->pluck('answer');


        $dataSkala = $answersSkalaStress->pluck('totalCategory3Section1');
        $labelsSkala = $answersSkalaStress->pluck('answer');


        return view('dashboard.pages.index', compact('labels', 'data', 'labelsSection2', 'dataSection2', 'labelsAcp', 'dataAcp', 'labelsSkala', 'dataSkala'));
    }
    public function exportExcel()
    {
        return Excel::download(new UserAnswersExport, 'user_answers.xlsx');
    }
    public function AcpExportExcel()
    {
        return Excel::download(new AcpAnswerExport, 'user_answers_acp.xlsx');
    }
    public function SkalaExportExcel()
    {
        return Excel::download(new SkalaStressExport, 'user_answers_skala.xlsx');
    }
    public function exportAllData()
    {
        return Excel::download(new AllDataExport, 'all_data.xlsx');
    }

    public function showAllAnswers(Request $request)
    {
        $categories = Category::all(); // Pastikan ini mengambil data kategori
        // Logika lain untuk mengambil jawaban, jika ada
        return view('dashboard.pages.all-answers', compact('categories'));
    }

    public function allAnswers1(Request $request)
    {
        $categoryId = $request->get('category');
        $answers = [];

        if ($categoryId) {
            // Pastikan untuk memuat relasi 'question' juga
            $answers = Answare::with('question')->where('category_id', $categoryId)->orderBy('created_at', 'desc')->paginate(10);
        }

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        return view('dashboard.pages.all-answers', compact('answers', 'categories'));
    }
    public function allAnswers(Request $request)
    {
        // Mendapatkan category id dari request
        $categoryId = $request->get('category');
        $answers = [];

        if ($categoryId) {
            // Query untuk mendapatkan jawaban berdasarkan kategori yang dipilih
            $answers = Answare::with('question')
                ->where('category_id', $categoryId)
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Menggunakan pagination dengan 10 item per halaman
        }

        // Mengambil semua kategori untuk dropdown
        $categories = Category::all();

        // Mengirim data ke view
        return view('dashboard.pages.all-answers', compact('answers', 'categories'));
    }
}
