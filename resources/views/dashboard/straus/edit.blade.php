@extends('dashboard.layouts.app')

@section('title', 'Straus Edit')

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
                <h1>Straus Question Edit Data</h1>
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

                        <form action="{{ route('straus.update', $question->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Kategori Selection -->
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $question->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pertanyaan -->
                            <div class="form-group">
                                <label for="question_text">Pertanyaan</label>
                                <textarea name="question_text" id="question_text" class="form-control" required>{{ old('question_text', $question->question_text) }}</textarea>
                            </div>

                            <!-- Section Selection -->
                            <div class="form-group" id="section_div"
                                style="{{ $question->category_id == 1 ? 'display: block;' : 'display: none;' }}">
                                <label for="section">Section</label>
                                <select name="section" id="section" class="form-control" required>
                                    <option value="" disabled>Pilih Section</option>
                                    <option value="1" {{ $question->section == 1 ? 'selected' : '' }}>Section 1
                                    </option>
                                    <option value="2" {{ $question->section == 2 ? 'selected' : '' }}>Section 2
                                    </option>
                                </select>
                            </div>

                            <!-- Tipe Jawaban -->
                            <div class="form-group">
                                <label for="question_type">Tipe Jawaban</label>
                                <input type="text" id="question_type" class="form-control" name="question_type" readonly
                                    value="{{ $question->question_type == 1 ? 'Single Select' : 'Multiple Select' }}">
                                <input type="hidden" name="question_type" id="question_type_hidden"
                                    value="{{ $question->question_type }}">
                            </div>

                            <!-- Options Section -->
                            <div class="form-group">
                                <label for="options">Options</label>
                                <div id="options-container">
                                    @foreach ($question->options as $option)
                                        <div class="option-group">
                                            <textarea name="option_url[]" class="form-control mb-2 option-input" placeholder="Enter Option URL">{{ old('option_url[]', $option->url) }}</textarea>
                                            <input type="text" name="option_description[]" class="form-control mb-2"
                                                placeholder="Option Description"
                                                value="{{ old('option_description[]', $option->description) }}">
                                            <div class="thumbnail-preview mb-2">
                                                @if ($option->url)
                                                    {!! $option->url !!}
                                                @else
                                                    <span>Preview will appear here...</span>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-danger remove-option-btn">Remove</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-option-btn" class="btn btn-secondary mt-2">Add
                                    Option</button>
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
            $('#category_id').change(function() {
                var strausId = '1'; // Adjust based on your category ID for Straus
                if ($(this).val() == strausId) {
                    $('#section_div').show();
                } else {
                    $('#section_div').hide();
                }
            });

            function createOptionGroup() {
                return `
                <div class="option-group">
                    <textarea name="option_url[]" class="form-control mb-2 option-input" placeholder="Enter Option URL"></textarea>
                    <input type="text" name="option_description[]" class="form-control mb-2" placeholder="Option Description">
                    <div class="thumbnail-preview mb-2">
                        <span>Preview will appear here...</span>
                    </div>
                    <button type="button" class="btn btn-danger remove-option-btn">Remove</button>
                </div>`;
            }

            $('#section').change(function() {
                var selectedSection = $(this).val();
                $('#options-container').empty();
                $('#add-option-btn').hide();

                if (selectedSection === '1') {
                    $('#question_type_hidden').val('1');
                    $('#question_type').val('Single Select');
                    $('#options-container').append(createOptionGroup());
                    $('.remove-option-btn').hide();
                    $('#add-option-btn').hide();
                } else if (selectedSection === '2') {
                    $('#question_type_hidden').val('2');
                    $('#question_type').val('Multiple Select');
                    $('#add-option-btn').show();
                }
            });

            $('#add-option-btn').click(function() {
                $('#options-container').append(createOptionGroup());
            });

            $(document).on('click', '.remove-option-btn', function() {
                $(this).parent('.option-group').remove();
            });

            $(document).on('input', '.option-input', function() {
                var embedCode = $(this).val();
                var previewContainer = $(this).siblings('.thumbnail-preview');
                if (embedCode.trim() === '') {
                    previewContainer.html('<span>Preview will appear here...</span>');
                } else {
                    previewContainer.html(embedCode);
                }
            });
            $('#add-option-btn').hide();
        });
    </script>
@endpush
