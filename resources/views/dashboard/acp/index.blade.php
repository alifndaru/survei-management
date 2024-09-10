@extends('dashboard.layouts.app')

@section('title', 'ACP')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>ACP Management</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('acp.create') }}" class="btn btn-primary">Create Data</a>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table-striped table-md table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question Text</th>
                                        <th>Section</th>
                                        <th>Type</th>
                                        <th>Options description video/Gif</th>
                                        <th>Created At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $question)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $question->question_text }}</td>
                                            <td>{{ $question->section ? 'Section ' . $question->section : 'N/A' }}</td>
                                            <td>{{ $question->question_type == 1 ? 'Single Select' : 'Multiple Select' }}
                                            </td>
                                            <td>
                                                @foreach ($question->options as $option)
                                                    <div>
                                                        <a href="{{ $option->option_url }}" target="_blank">
                                                            {{ $option->option_description }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>{{ $question->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('acp.edit', $question->id) }}"
                                                    class="btn btn-warning">Edit</a>
                                                <form action="{{ route('acp.destroy', $question->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <!-- Page Specific JS File -->
@endpush
