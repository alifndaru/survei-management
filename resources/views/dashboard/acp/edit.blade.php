@extends('dashboard.layouts.app')

@section('title', 'ACP Edit')

@push('style')
    <style>
        .thumbnail-preview {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 10px;
            width: 200px;
        }

        .thumbnail-preview img,
        .thumbnail-preview video {
            width: 150px;
            height: 100px;
            object-fit: cover;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Question</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Your Question</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <br>

                        <form action="{{ route('acp.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Kategori Selection -->
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select name="category_id" id="category_id" class="form-control" disabled>
                                    @foreach ($categories as $category)
                                        @if ($category->id == 2)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="category_id" value="2">
                            </div>

                            <!-- Pertanyaan -->
                            <div class="form-group">
                                <label for="question_text">Pertanyaan</label>
                                <textarea name="question_text" id="question_text" class="form-control" required>{{ old('question_text', $question->question_text) }}</textarea>
                            </div>

                            <!-- Section Selection -->
                            <div class="form-group">
                                <label for="section">Section</label>
                                <select name="section" id="section" class="form-control" disabled>
                                    <option value="1" selected>Section 1</option>
                                </select>
                                <input type="hidden" name="section" value="1">
                            </div>

                            <!-- Tipe Jawaban -->
                            <div class="form-group">
                                <label for="question_type">Tipe Jawaban</label>
                                <input type="text" id="question_type" class="form-control" name="question_type" readonly
                                    value="Single Select">
                                <input type="hidden" name="question_type" id="question_type_hidden" value="1">
                            </div>

                            <!-- Option Description -->
                           @foreach ($options as $option)
                                <div class="form-group">
                                    <label for="option_description">Option Description</label>
                                    <input type="text" name="option_description" class="form-control mb-2"
                                        placeholder="isi seperti pertanyaan"
                                        value="{{ old('option_description.' . $loop->index, $option->option_description ?? '') }}">
                                    @if ($errors->has('option_description.' . $loop->index))
                                        <span class="help-block">{{ $errors->first('option_description.' . $loop->index) }}</span>
                                    @endif
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Update Pertanyaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
