<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcpSurveiController extends Controller
{
    // public function finish()
    // {
    //     // Tandai survei ACP sebagai selesai di session
    //     session(['completed_acp_survey' => true]);

    //     // Arahkan ke survei Skala Stress jika ACP selesai
    //     return redirect()->route('skala-stress-survei.index');
    // }

    public function index(Request $request)
    {
        if (session('user_id') === null) {
            return redirect()->route('users.index');
        }
        $acp = Questions::with('options')
            ->where('section', 1)
            ->where('category_id', 2)
            ->get();

        $currentQuestionIndex = $request->query('q', 0);

        if ($currentQuestionIndex < $acp->count()) {
            $currentQuestion = $acp[$currentQuestionIndex];
            $hasNext = ($currentQuestionIndex + 1) < $acp->count();
            $section = 1;
        } else {
            session(['completed_acp_survey' => true]);

            // Arahkan ke survei Skala Stress jika ACP selesai
            // return redirect()->route('skala-stress-survei.index');
            return redirect()->route('straus-survei.completion-options');
        }

        return view('users.acp.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:pernah,tidak pernah|string',
            'category_id' => 'required|exists:categories,id',
            'current_question_index' => 'required|integer'
        ], [
            'answer' => 'Jawaban harus diisi'
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
        return redirect()->route('acp-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }
}
