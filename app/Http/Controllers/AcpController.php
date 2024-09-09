<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Options;
use App\Models\Questions;
use Illuminate\Http\Request;

class AcpController extends Controller
{
    public function index()
    {
        $questions = Questions::with('options')->where('category_id', 2)->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.acp.index', compact('questions'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.acp.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'question_type' => 'required|in:1,2',
            'question_text' => 'required|string',
            'section' => 'required|in:1,2',
            'option_url' => 'required',
            'option_description' => 'required',
        ]);

        // Create the question
        $question = Questions::create([
            'category_id' => $request->input('category_id'),
            'question_type' => $request->input('question_type'),
            'section' => $request->input('section'),
            'question_text' => $request->input('question_text'),
        ]);

        // Create associated options
        Options::create([
            'question_id' => $question->id,
            'option_url' => $request->input('option_url'),
            'option_description' => $request->input('option_description'),
        ]);

        return redirect()->route('acp.create')->with('success', 'Pertanyaan dan opsi berhasil disimpan.');
    }

    public function edit($id)
    {
        $question = Questions::with('options')->findOrFail($id);
        $categories = Category::all();
        $options = Options::where('question_id', $id)->get();
        return view('dashboard.acp.edit', compact('question', 'categories', 'options'));
    }
    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'section' => 'required|in:1,2',
            'question_type' => 'required|in:1,2',
            'option_url' => 'required|string',
            'option_description' => 'required|string',
        ]);

        // Find the existing question by ID
        $question = Questions::findOrFail($id);
        $question->category_id = $request->input('category_id');
        $question->question_text = $request->input('question_text');
        $question->section = $request->input('section');
        $question->question_type = $request->input('question_type');
        $question->save();

        // Check if the option exists for this question
        $option = Options::where('question_id', $question->id)->first();

        if ($option) {
            // Update existing option
            $option->option_url = $request->input('option_url');
            $option->option_description = $request->input('option_description');
            $option->save();
        } else {
            // If no option exists, create a new one
            Options::create([
                'question_id' => $question->id,
                'option_url' => $request->input('option_url'),
                'option_description' => $request->input('option_description'),
            ]);
        }

        // Redirect back with success message
        return redirect()->route('acp.edit', $question->id)->with('success', 'Pertanyaan berhasil diperbarui.');
    }
}
