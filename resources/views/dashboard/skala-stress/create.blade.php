@extends('dashboard.layouts.app')

@section('title', 'ACP Create')

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

        .thumbnail-preview div {
            margin-right: 10px;
            margin-bottom: 10px;
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
                <h1>Skala Stress Question Create Data</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Write Your Question</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('skala-stress.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

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
                                @if ($errors->has('category_id'))
                                    <span class="help-block">{{ $errors->first('category_id') }}</span>
                                @endif
                            </div>

                            <!-- Pertanyaan -->
                            <div class="form-group">
                                <label for="question_text">Pertanyaan</label>
                                <textarea name="question_text" id="question_text" class="form-control" required></textarea>
                                @if ($errors->has('question_text'))
                                    <span class="help-block">{{ $errors->first('question_text') }}</span>
                                @endif
                            </div>

                            <!-- Section Selection -->
                            <div class="form-group">
                                <label for="section">Section</label>
                                <select name="section" id="section" class="form-control" disabled>
                                    <option value="1" selected>Section 1</option>
                                </select>
                                <input type="hidden" name="section" value="1">
                                @if ($errors->has('section'))
                                    <span class="help-block">{{ $errors->first('section') }}</span>
                                @endif
                            </div>

                            <!-- Tipe Jawaban -->
                            <div class="form-group">
                                <label for="question_type">Tipe Jawaban</label>
                                <input type="text" id="question_type" value="1" class="form-control" disabled>
                                <input type="hidden" name="question_type" value="1">
                                @if ($errors->has('question_type'))
                                    <span class="help-block">{{ $errors->first('question_type') }}</span>
                                @endif
                            </div>
                            <div class="option-group">
                                <input type="text" name="option_description" class="form-control mb-2"
                                    placeholder="Isi seperti pertanyaan">
                                @if ($errors->has('option_description'))
                                    <span class="help-block">{{ $errors->first('option_description') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Pertanyaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
