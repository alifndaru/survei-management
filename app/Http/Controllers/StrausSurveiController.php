<?php

namespace App\Http\Controllers;

use App\Models\Answare;
use App\Models\Options;
use App\Models\Questions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StrausSurveiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua pertanyaan dari section 1 kategori 1
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
                return redirect()->route('straus-survei.index');
            }
        }

        return view('users.straus.index', compact('currentQuestion', 'currentQuestionIndex', 'hasNext', 'section'));
    }

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'question_id' => 'required|exists:questions,id',
    //         'question_id' => 'required|exists:questions,id',
    //         'answer' => 'required|in:yes,no', //validasi section 1
    //         'answers' => 'nullable|array', // Validasi answers sebagai array
    //         'answers.*' => 'string', // Validasi setiap answer harus ada di tabel options
    //         'category_id' => 'required|exists:categories,id'
    //     ]);
    //     $userId = session('user_id');

    //     if (!$userId) {
    //         return redirect()->route('/'); // Arahkan ke halaman registrasi jika user_id tidak ada
    //     }
    //     Answare::create([
    //         'user_id' => $userId,
    //         'question_id' => $request->input('question_id'),
    //         'answer' => $request->input('answer'),
    //         'category_id' => $request->input('category_id')
    //     ]);
    //     // Redirect ke pertanyaan berikutnya atau ke halaman selesai
    //     return redirect()->route('straus-survei.index', ['q' => $request->input('current_question_index') + 1]);
    // }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'nullable|in:yes,no', // validasi section 1
            'answers' => 'nullable|array', // Validasi answers sebagai array
            'answers.*' => 'string', // Validasi setiap answer harus string
            'category_id' => 'required|exists:categories,id'
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('/'); // Arahkan ke halaman registrasi jika user_id tidak ada
        }

        // Simpan jawaban untuk Section 1
        if ($request->filled('answer')) {
            Answare::create([
                'user_id' => $userId,
                'question_id' => $request->input('question_id'),
                'answer' => $request->input('answer'),
                'category_id' => $request->input('category_id')
            ]);
        }

        // Simpan jawaban untuk Section 2
        if ($request->filled('answers')) {
            foreach ($request->input('answers', []) as $answerDescription) {
                Answare::create([
                    'user_id' => $userId,
                    'question_id' => $request->input('question_id'),
                    'answer' => $answerDescription,
                    'category_id' => $request->input('category_id')
                ]);
            }
        }

        // Redirect ke pertanyaan berikutnya atau ke halaman selesai
        return redirect()->route('straus-survei.index', ['q' => $request->input('current_question_index') + 1]);
    }
}
