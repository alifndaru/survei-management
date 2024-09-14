{{-- @extends('users.layouts.app')
@section('title', 'Completion Options')
@section('main')
    <div class="main-content py-5">
        <section class="section">
            <div class="section-header mb-4">
                <h1 class="display-5 fw-bold text-center text-primary">Completion Options</h1>
            </div>
            <div class="section-body container text-center">
                @if (!$hasCompletedAcp && $hasCompletedStraus)
                    <a href="{{ route('acp-survei.index') }}" class="btn btn-primary btn-lg m-2">ACP</a>
                @endif

                @if (!$hasCompletedStressScale && $hasCompletedAcp)
                    <a href="{{ route('skala-stress-survei.index') }}" class="btn btn-secondary btn-lg m-2">Skala Stress</a>
                @endif
            </div>
        </section>
    </div>
@endsection --}}
@extends('users.layouts.app')
@section('title', 'Completion Options')
@section('main')
    <div class="main-content py-5">
        <section class="section">
            <div class="section-header mb-4">
                <h1 class="display-5 fw-bold text-center text-primary">Completion Options</h1>
            </div>
            <div class="section-body container text-center">
                @if (!$hasCompletedAcp && $hasCompletedStraus)
                    <a href="{{ route('acp-survei.index') }}" class="btn btn-primary btn-lg m-2">ACP</a>
                @else
                    <button class="btn btn-primary btn-lg m-2" disabled>ACP (Completed)</button>
                @endif

                @if (!$hasCompletedStressScale && $hasCompletedStraus)
                    <a href="{{ route('skala-stress-survei.index') }}" class="btn btn-secondary btn-lg m-2">Skala Stress</a>
                @else
                    <button class="btn btn-secondary btn-lg m-2" disabled>Skala Stress (Completed)</button>
                @endif
            </div>
        </section>
    </div>
@endsection
