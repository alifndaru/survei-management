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
            // session(['completed_acp_survey' => true]);

            // Arahkan ke survei Skala Stress jika ACP selesai
            // return redirect()->route('skala-stress-survei.index');

            return redirect()->route('skala-stress-survei.index');
        }

        return view('users.acp.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:sangat sesuai,sesuai,netral,tidak sesuai,sangat tidak sesuai|string',
            'current_question_index' => 'required|integer',
        ], [
            'answer' => 'Jawaban harus diisi'
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('users.index');
        }

        if ($request->filled('answer')) {
            $answer = $request->input('answer');
            $nilai = $this->getNilaiFromFrequency($answer);

            Answare::create([
                'user_id' => $userId,
                'question_id' => $request->input('question_id'),
                'answer' => $answer,
                'category_id' => $request->input('category_id'),
                'nilai' => $nilai
            ]);
        }

        return redirect()->route('acp-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }

    private function getNilaiFromFrequency($answer)
    {
        switch ($answer) {
            case 'sangat sesuai':
                return 5;
            case 'sesuai':
                return 4;
            case 'netral':
                return 3;
            case 'tidak sesuai':
                return 2;
            case 'sangat tidak sesuai':
            default:
                return 1;
        }
    }
}
