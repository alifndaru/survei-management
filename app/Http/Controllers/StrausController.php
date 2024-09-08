<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Options;
use App\Models\Questions;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
