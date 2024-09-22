<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Options;
use App\Models\Questions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StrausSurveiController extends Controller
{
    public function finish()
    {
        // Delete session cookies
        session()->forget('user_id');

        return view('users.finish');
    }
    public function index(Request $request)
    {
        if (session('user_id') === null) {
            return redirect()->route('users.index');
        }

        $section1Questions = Questions::with('options')
            ->where('section', 1)
            ->where('category_id', 1) // kategori 1
            ->get();

        // Ambil semua pertanyaan dari section 2 kategori 1
        $section2Questions = Questions::with('options')
            ->where('section', 2)
            ->where('category_id', 1) // kategori 1
            ->get();


        // Tentukan indeks pertanyaan saat ini (mulai dari 0)
        $currentQuestionIndex = $request->query('q', 0);


        // Jika masih ada pertanyaan di section 1 yang belum dijawab
        if ($currentQuestionIndex < $section1Questions->count()) {
            $currentQuestion = $section1Questions->get($currentQuestionIndex);
            $hasNext = ($currentQuestionIndex + 1) < $section1Questions->count();
            $section = 1;
        }
        // Jika semua pertanyaan section 1 sudah dijawab, lanjutkan ke section 2
        else {
            // Menghitung indeks untuk section 2
            $section2Index = $currentQuestionIndex - $section1Questions->count();
            if ($section2Index < $section2Questions->count()) {
                $currentQuestion = $section2Questions->get($section2Index);
                $hasNext = ($section2Index + 1) < $section2Questions->count();
                $section = 2;
            } else {
                // Jika sudah selesai semua, arahkan ke halaman akhir atau halaman sukses
                // return redirect()->route('straus-survei.index');
                // session(['completed_straus_survey' => true]);

                return redirect()->route('acp-survei.index');
                // return redirect()->route('straus-survei.completion-options');
            }
        }

        return view('users.straus.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => $request->input('section') == 1 ? 'required|in:pernah,tidak pernah' : 'nullable',
            'answers' => $request->input('section') == 2 ? 'required|array|min:1' : 'nullable',
            'answers.*' => 'string',
            'category_id' => 'required|exists:categories,id',
        ], [
            'answer.required' => 'Jawaban harus diisi',
            'answers.required' => 'Jawaban harus diisi'
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('users.index');
        }

        // Simpan jawaban untuk Section 1
        if ($request->filled('answer')) {
            $answer = $request->input('answer');
            if ($answer === 'pernah') {
                $answer = $request->input('frequency');
            }
            $nilai = $this->getNilaiFromFrequency($answer);
            Answare::create([
                'user_id' => $userId,
                'question_id' => $request->input('question_id'),
                'answer' => $answer,
                'category_id' => $request->input('category_id'),
                'nilai' => $nilai
            ]);
        }

        if ($request->filled('answers')) {
            foreach ($request->input('answers', []) as $answerDescription) {
                if ($answerDescription === 'tidak pernah') {
                    $frequency = 'tidak pernah';
                    $nilai = 1;
                } else {
                    $frequencyKey = "frequency_" . str_replace(' ', '_', trim($answerDescription));
                    $frequency = $request->input($frequencyKey);
                    // Tentukan nilai berdasarkan frekuensi
                    if ($frequency === 'selalu') {
                        $nilai = 4;
                    } elseif ($frequency === 'sering') {
                        $nilai = 3;
                    } elseif ($frequency === 'jarang') {
                        $nilai = 2;
                    } else {
                        $nilai = 1;
                    }
                }
                // Simpan jawaban
                Answare::create([
                    'user_id' => $userId,
                    'question_id' => $request->input('question_id'),
                    'answer' => $frequency,
                    'category_id' => $request->input('category_id'),
                    'nilai' => $nilai,
                ]);
            }
        }
        // Redirect ke pertanyaan berikutnya atau ke halaman selesai
        return redirect()->route('straus-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }

    private function getNilaiFromFrequency($frequency)
    {
        switch ($frequency) {
            case 'selalu':
                return 4;
            case 'sering':
                return 3;
            case 'jarang':
                return 2;
            case 'tidak pernah':
            default:
                return 1; // Default untuk 'tidak pernah'
        }
    }


    public function showCompletionOptions1()
    {
        return view('users.straus.completion-options');
    }

    public function showCompletionOptions()
    {
        // Cek apakah pengguna sudah menyelesaikan survei di session
        $hasCompletedAcp = session('completed_acp_survey', false);
        $hasCompletedStressScale = session('completed_stress_scale_survey', false);
        $hasCompletedStraus = session('completed_straus_survey', false);
        // dd(session()->all());

        if ($hasCompletedAcp && $hasCompletedStressScale && $hasCompletedStraus) {
            return redirect()->route('finish'); // Ganti 'finish' dengan route nama yang sesuai
        }

        // Tampilkan halaman Completion Options
        return view('users.straus.completion-options', [
            'hasCompletedAcp' => $hasCompletedAcp,
            'hasCompletedStressScale' => $hasCompletedStressScale,
            'hasCompletedStraus' => $hasCompletedStraus
        ]);
    }



    public function completeSurvey(Request $request)
    {
        // Cek survei mana yang dipilih pengguna
        if ($request->category_id === '1') {
            // Tandai Straus sebagai selesai di session
            session(['completed_straus_survey' => true]);

            // Arahkan ke halaman opsi untuk melanjutkan ke ACP atau Skala Stress
            return redirect()->route('straus-survei.completion-options');
        } elseif ($request->category_id === '2') {
            // Tandai ACP sebagai selesai di session
            session(['completed_acp_survey' => true]);

            // Jika ACP selesai, arahkan ke survei Skala Stress
            return redirect()->route('skala-stress-survei.index');
        } elseif ($request->category_id === '3') {
            // Tandai Skala Stress sebagai selesai di session
            session(['completed_stress_scale_survey' => true]);
            session()->flush(); // This line deletes all session data
            // Jika semua survei selesai, arahkan ke halaman akhir atau beri pesan sukses
            return redirect()->route('finish');
        }
    }
}
