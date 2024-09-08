@extends('users.layouts.app')

@section('title', 'Question')

@push('style')
    <!-- Bootstrap CSS -->
@endpush

@section('main')
    <div class="main-content py-5">
        <section class="section">
            <div class="section-header mb-4">
                <h1 class="display-5 fw-bold text-center text-primary">
                    {{ $section == 1 ? 'Section 1: ' : 'Section 2: ' }}
                    {{ $currentQuestion->question_text }}
                </h1>
            </div>

            <div class="section-body container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 bg-light p-5 rounded shadow-sm">
                        {{-- <form action=""> --}}
                        <form action="{{ route('straus-survei.store') }}" method="POST">

                            @csrf
                            <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                            <input type="hidden" name="current_question_index" value="{{ $currentQuestionIndex }}">
                            <input type="hidden" name="section" value="{{ $section }}">
                            <input type="hidden" name="category_id" value="{{ $currentQuestion->category_id }}">



                            <!-- Menampilkan option_url jika ada -->
                            @if ($currentQuestion->options->isNotEmpty())
                                <div class="mb-4">
                                    <ul class="list-group list-group-flush text-center">
                                        @foreach ($currentQuestion->options as $option)
                                            <li class="list-group-item">
                                                <a href="{{ $option->option_url }}" target="_blank"
                                                    class="text-primary text-decoration-underline">{!! $option->option_url !!}</a>
                                                <p class="mb-1 text-muted">{{ $option->option_description }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Jawaban "Yes/No" -->
                            <div class="mb-4 text-center">
                                <label class="form-label fw-semibold text-secondary">Your Answer:</label>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="answer" id="yes"
                                            value="yes">
                                        <label class="form-check-label" for="yes">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer" id="no"
                                            value="no">
                                        <label class="form-check-label" for="no">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                {{-- <a href="?q={{ $currentQuestionIndex + 1 }}" class="btn btn-primary btn-lg">
                                    {{ $hasNext ? 'Next' : ($section == 1 ? 'Continue to Section 2' : 'Finish') }}
                                </a> --}}
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ $hasNext ? 'Next' : ($section == 1 ? 'Continue to Section 2' : 'Finish') }}
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- Bootstrap JS -->
@endpush
