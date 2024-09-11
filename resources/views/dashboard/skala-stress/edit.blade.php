@extends('dashboard.layouts.app')

@section('title', 'Skala-Stress Edit')

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

                        <form action="{{ route('skala-stress.update', $question->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Kategori Selection -->
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select name="category_id" id="category_id" class="form-control" disabled>
                                    @foreach ($categories as $category)
                                        @if ($category->id == 3)
                                            <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="category_id" value="3">
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

                            <!-- Option URL -->
                            <div class="form-group">
                                <label for="option_url">Option URL</label>
                                <textarea name="option_url" id="option_url" class="form-control mb-2" placeholder="Enter Option URL">{{ old('option_url', $option->option_url ?? '') }}</textarea>
                                @if ($errors->has('option_url'))
                                    <span class="help-block">{{ $errors->first('option_url') }}</span>
                                @endif
                            </div>

                            <!-- Option Description -->
                            <div class="form-group">
                                <label for="option_description">Option Description</label>
                                <input type="text" name="option_description" class="form-control mb-2"
                                    placeholder="Option Description"
                                    value="{{ old('option_description', $option->option_description ?? '') }}">
                                @if ($errors->has('option_description'))
                                    <span class="help-block">{{ $errors->first('option_description') }}</span>
                                @endif
                            </div>

                            <!-- Thumbnail Preview Section -->
                            <div class="thumbnail-preview mb-2">
                                <span>Preview will appear here...</span>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Pertanyaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('input', '#option_url', function() {
                var embedCode = $(this).val();
                var previewContainer = $('.thumbnail-preview');
                if (embedCode.trim() === '') {
                    previewContainer.html('<span>Preview will appear here...</span>');
                } else {
                    previewContainer.html(
                        embedCode); // Adjust this for specific image/video embedding logic
                }
            });
        });
    </script>
@endpush
