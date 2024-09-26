@extends('users.layouts.app')

@section('title', 'Question ACP')

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
                    {{ 'ACP: ' . $currentQuestion->question_text }}
                </h1>
            </div>

            <div class="section-body container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 bg-light p-5 rounded shadow-sm">
                        <form action="{{ route('acp-survei.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                            <input type="hidden" name="current_question_index" value="{{ $currentQuestionIndex }}">
                            <input type="hidden" name="category_id" value="{{ $currentQuestion->category_id }}">

                            @if ($currentQuestion->options->isNotEmpty())
                                @if ($errors->has('answer'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('answer') }}
                                    </div>
                                @endif
                                <div class="mb-4">
                                    <ul class="list-group list-group-flush text-center">
                                        @foreach ($currentQuestion->options as $option)
                                            <li class="list-group-item">
                                                <p class="mb-1 text-muted">{{ $option->option_description }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                           <!-- Jawaban user -->
                            <div class="mb-4 text-center">
                                <label class="form-label fw-semibold text-secondary">Your Answer:</label>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="answer" id="sangat-sesuai" value="sangat sesuai" onchange="setNilai(5)">
                                        <label class="form-check-label" for="sangat-sesuai">Sangat Sesuai</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="answer" id="sesuai" value="sesuai" onchange="setNilai(4)">
                                        <label class="form-check-label" for="sesuai">Sesuai</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="answer" id="netral" value="netral" onchange="setNilai(3)">
                                        <label class="form-check-label" for="netral">Netral</label>
                                    </div>
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="answer" id="tidak-sesuai" value="tidak sesuai" onchange="setNilai(2)">
                                        <label class="form-check-label" for="tidak-sesuai">Tidak Sesuai</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answer" id="sangat-tidak-sesuai" value="sangat tidak sesuai" onchange="setNilai(1)">
                                        <label class="form-check-label" for="sangat-tidak-sesuai">Sangat Tidak Sesuai</label>
                                    </div>
                                    <input type="hidden" name="nilai" id="nilai" value="">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ $hasNext ? 'Next' : 'Continue to Skala stress' }}
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
<script>
    function setNilai(nilai) {
    const nilaiInput = document.getElementById('nilai');
    if (nilaiInput) {
        nilaiInput.value = nilai;
    }
}

</script>

@endpush
