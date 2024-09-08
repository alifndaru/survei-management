<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Options;
use App\Models\Questions;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class StrausController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Questions::with('options')->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.straus.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        // dd($categories);

        return view('dashboard.straus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'question_type' => 'required|in:1,2',
            'question_text' => 'required|string',
            'section' => 'nullable|in:1,2',
            'option_url' => 'required|array',
            'option_url.*' => 'string',
            'option_description' => 'required|array',
            'option_description.*' => 'string',
        ]);

        // Create the question
        $question = Questions::create([
            'category_id' => $request->input('category_id'),
            'question_type' => $request->input('question_type'),
            'section' => $request->input('section'),
            'question_text' => $request->input('question_text'),
        ]);

        // Create associated options
        foreach ($request->input('option_url') as $index => $optionUrl) {
            Options::create([
                'question_id' => $question->id,
                'option_url' => $optionUrl,
                'option_description' => $request->input('option_description')[$index],
            ]);
        }

        return redirect()->route('straus.create')->with('success', 'Pertanyaan dan opsi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Questions::with('options')->findOrFail($id);
        $categories = Category::all();
        return view('dashboard.straus.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'section' => 'nullable|in:1,2',
            'question_type' => 'required|in:1,2',
            'option_url.*' => 'nullable|string',
            'option_description.*' => 'nullable|string',
        ]);

        $question = Questions::findOrFail($id);
        $question->category_id = $request->input('category_id');
        $question->question_text = $request->input('question_text');
        $question->section = $request->input('section');
        $question->question_type = $request->input('question_type');
        $question->save();

        // Menghapus opsi lama yang terkait dengan pertanyaan
        $question->options()->delete();

        // Menyimpan opsi baru
        $optionUrls = $request->input('option_url', []);
        $optionDescriptions = $request->input('option_description', []);

        foreach ($optionUrls as $index => $url) {
            if (!empty($url)) {
                Options::create([
                    'question_id' => $question->id,
                    'option_url' => $url,
                    'option_description' => $optionDescriptions[$index] ?? '',
                ]);
            }
        }

        return redirect()->route('straus.edit', $question->id)->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $question = Questions::with('options', 'answare')->findOrFail($id);
        // $question->options()->delete();
        // $question->answare()->delete();

        // $question->delete();
        try {
            $question = Questions::with('options', 'answare')->findOrFail($id);
            $question->options()->delete();
            $question->answare()->delete();

            $question->delete();
            return redirect()->route('straus.index')->with('success', 'Pertanyaan berhasil dihapus.');
        } catch (\Exception $e) {
            // Log the error or return an error message
            return redirect()->route('straus.index')->with('error', 'Error deleting question: ' . $e->getMessage());
        }



        // $question = Questions::with('options')->findOrFail($id);
        // foreach ($question->options as $option) {
        //     $option->delete();
        // }
        // $selectedAnswers = $question->answare;
        // foreach ($selectedAnswers as $selectedAnswer) {
        //     $selectedAnswer->delete();
        // }
        // $question->delete();
        // return redirect()->route('straus.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
