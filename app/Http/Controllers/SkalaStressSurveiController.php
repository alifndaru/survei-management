<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Questions;
use Illuminate\Http\Request;

class SkalaStressSurveiController extends Controller
{

    // public function finish()
    // {
    //     // Tandai survei Skala Stress sebagai selesai di session
    //     session(['completed_stress_scale_survey' => true]);

    //     // Arahkan ke halaman akhir atau berikan pesan selesai
    //     return redirect()->route('finish');
    // }

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
            // Tandai survei Skala Stress sebagai selesai di session
            // session(['completed_stress_scale_survey' => true]);

            // Arahkan ke halaman akhir atau berikan pesan selesai
            return redirect()->route('finish');
            // return redirect()->route('straus-survei.completion-options');
        }

        return view('users.skala-stress.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:sangat sesuai,sesuai,netral,tidak sesuai,sangat tidak sesuai|string',
            // 'category_id' => 'required|exists:categories,id',
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

        // Periksa apakah ini pertanyaan terakhir
        $totalQuestions = Questions::where('section', 1)->where('category_id', 3)->count();
        $nextQuestionIndex = $request->input('current_question_index') + 1;
        if ($nextQuestionIndex >= $totalQuestions) {
            // Jika ya, redirect ke halaman finish
            return redirect()->route('finish');
        } else {
            // Jika tidak, lanjutkan ke pertanyaan berikutnya
            return redirect()->route('skala-stress-survei.index', ['q' => $nextQuestionIndex]);
        }
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
