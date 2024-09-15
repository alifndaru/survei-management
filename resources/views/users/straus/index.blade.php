@extends('users.layouts.app')

@section('title', 'Question')

@push('style')
    <style>
        /* Ensure the video container and cards are consistently sized */
        .video-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            margin-bottom: 1rem;
        }

        .video-container iframe,
        .video-container video,
        .video-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card {
            height: 100%;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card p {
            margin-top: auto;
            /* Ensure text is at the bottom */
        }

        /* Ensure uniform spacing between cards */
        .row-cols-1 .col,
        .row-cols-md-2 .col {
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('main')
    <div class="main-content py-5">
        <section class="section">
            <div class="section-header mb-4">
                <h1 class="display-5 fw-bold text-center text-primary">
                    {{ $section == 1 ? 'Straus Section 1: ' : 'Straus Section 2: ' }}
                    {{ $currentQuestion->question_text }}
                </h1>
            </div>

            <div class="section-body container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 bg-light p-5 rounded shadow-sm">
                        <form action="{{ route('straus-survei.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                            <input type="hidden" name="current_question_index" value="{{ $currentQuestionIndex }}">
                            <input type="hidden" name="section" value="{{ $section }}">
                            <input type="hidden" name="category_id" value="{{ $currentQuestion->category_id }}">

                            @if ($section == 1)
                                @if ($errors->has('answer'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('answer') }}
                                    </div>
                                @endif
                                @if ($currentQuestion->options->isNotEmpty())
                                    <div class="mb-4">
                                        <ul class="list-group list-group-flush text-center">
                                            @foreach ($currentQuestion->options as $option)
                                                <li class="list-group-item">
                                                    <div class="video-container">
                                                        {!! $option->option_url !!}
                                                    </div>
                                                    <p class="mb-1 text-muted">{{ $option->option_description }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mb-4 text-center">
                                    <label class="form-label fw-semibold text-secondary">Your Answer:</label>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="radio" name="answer" id="pernah"
                                                value="pernah">
                                            <label class="form-check-label" for="pernah">pernah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answer" id="tidak pernah"
                                                value="tidak pernah">
                                            <label class="form-check-label" for="tidak pernah">tidak pernah</label>
                                        </div>
                                    </div>
                                </div>
                            @elseif($section == 2)
                                <i>*Isi setidaknya salah satu</i>
                                @if ($errors->has('answers'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('answers') }}
                                    </div>
                                @endif
                                @if ($currentQuestion->options->isNotEmpty())
                                    <div class="mb-4">
                                        <div class="row row-cols-1 row-cols-md-2 g-4">
                                            @foreach ($currentQuestion->options as $option)
                                                <div class="col">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="answers[]"
                                                                    value="{{ $option->option_description }}">
                                                                <label
                                                                    class="form-check-label d-flex flex-column align-items-start"
                                                                    for="option_{{ $option->id }}">
                                                                    <!-- Video/Image Container -->
                                                                    <div class="ratio ratio-16x9 mb-2">
                                                                        {!! $option->option_url !!}
                                                                    </div>
                                                                    <!-- Option description -->
                                                                    <p class="text-muted">
                                                                        {{ $option->option_description }}
                                                                    </p>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif


                            <div class="text-center">
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
