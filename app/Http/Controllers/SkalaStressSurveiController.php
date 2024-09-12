<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Questions;
use Illuminate\Http\Request;

class SkalaStressSurveiController extends Controller
{

    public function finish()
    {
        // Tandai survei Skala Stress sebagai selesai di session
        session(['completed_stress_scale_survey' => true]);

        // Arahkan ke halaman akhir atau berikan pesan selesai
        return redirect()->route('finish');
    }

    public function index(Request $request)
    {
        if (session('user_id') === null) {
            return redirect()->route('users.index');
        }
        $skalaStress = Questions::with('options')
            ->where('section', 1)
            ->where('category_id', 3)
            ->get();

        $currentQuestionIndex = $request->query('q', 0);

        if ($currentQuestionIndex < $skalaStress->count()) {
            $currentQuestion = $skalaStress[$currentQuestionIndex];
            $hasNext = ($currentQuestionIndex + 1) < $skalaStress->count();
            $section = 1;
        } else {
            return redirect()->route('straus-survei.completion-options');
        }

        return view('users.skala-stress.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string|in:Sangat Tidak Setuju,Tidak Setuju,Netral,Setuju,Sangat Setuju',
            'category_id' => 'required|exists:categories,id',
            'current_question_index' => 'required|integer'
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('users.index');
        }

        Answare::create([
            'user_id' => $userId,
            'question_id' => $request->input('question_id'),
            'answer' => $request->input('answer'),
            'category_id' => $request->input('category_id')
        ]);

        // Pastikan 'q' adalah parameter yang benar untuk menunjukkan indeks pertanyaan saat ini
        return redirect()->route('skala-stress-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }
}
