@extends('users.layouts.app')
@section('title', 'Completion Options')
@section('main')
    <div class="main-content py-5">
        <section class="section">
            <div class="section-header mb-4">
                <h1 class="display-5 fw-bold text-center text-primary">Completion Options</h1>
            </div>
            <div class="section-body container text-center">
                <a href="{{ route('acp-survei.index') }}" class="btn btn-primary btn-lg m-2">ACP</a>
                <a href="#" class="btn btn-secondary btn-lg m-2">Skala Stress</a>
            </div>
        </section>
    </div>
@endsection