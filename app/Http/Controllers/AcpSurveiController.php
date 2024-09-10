<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Questions;
use Illuminate\Http\Request;

class AcpSurveiController extends Controller
{
    public function index(Request $request)
    {

        $acp = Questions::with('options')
            ->where('section', 1)
            ->where('category_id', 2)
            ->get();

        $currentQuestionIndex = $request->query('q', 0);

        if ($currentQuestionIndex < $acp->count()) {
            $currentQuestion = $acp[$currentQuestionIndex]; // Gunakan array access karena $acp adalah collection
            $hasNext = ($currentQuestionIndex + 1) < $acp->count();
            $section = 1;
        } else {
            return redirect()->route('straus-survei.completion-options');
        }
        return view('users.acp.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:pernah,tidak pernah|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        $userId = session('user_id');

        if (!$userId) {
            // Asumsi '/login' adalah route untuk halaman login, sesuaikan dengan aplikasi Anda
            return redirect()->route('login'); // Pastikan ini adalah route yang benar untuk halaman login atau registrasi
        }

        Answare::create([
            'user_id' => $userId,
            'question_id' => $request->input('question_id'),
            'answer' => $request->input('answer'),
            'category_id' => $request->input('category_id')
        ]);

        // Pastikan 'q' adalah parameter yang benar untuk menunjukkan indeks pertanyaan saat ini
        return redirect()->route('straus-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }
}
