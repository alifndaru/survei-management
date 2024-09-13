@extends('dashboard.layouts.app')

@section('title', 'Data Answers')

@push('style')
    <!-- Tambahkan style khusus jika diperlukan -->
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }

        .empty-state {
            padding: 40px 0;
            text-align: center;
            color: #999;
        }

        /* Styling pagination */
        .pagination {
            display: flex;
            justify-content: center;
            padding-top: 20px;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .pagination .page-item .page-link {
            color: #007bff;
        }

        .pagination .page-link {
            padding: 8px 16px;
            border-radius: 5px;
            margin: 0 5px;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Answers</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Pilih Kategori untuk Melihat Jawaban</h4>
                    </div>
                    <div class="card-body">

                        <!-- Form untuk memilih kategori -->
                        <form action="{{ route('all-answers') }}" method="GET">
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        <!-- Tampilkan pesan jika ada -->
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
                            @if (!empty($answers) && count($answers) > 0)
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban</th>
                                            <th>Waktu</th>
                                            <!-- Tambahkan lebih banyak kolom jika diperlukan -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                            $perPage = $answers->perPage(); // Jumlah item per halaman
                                            $currentPage = $answers->currentPage(); // Halaman saat ini
                                            $offset = ($currentPage - 1) * $perPage; // Menghitung offset
                                        @endphp
                                        @foreach ($answers as $answer)
                                            <tr>
                                                <td>{{ $offset + $loop->iteration }}</td>
                                                <td>{{ $answer->question->question_text }}</td>
                                                <td>{{ $answer->answer }}</td>
                                                <td>{{ $answer->created_at->format('Y-m-d H:i:s') }}</td>
                                                <!-- Tampilkan data lainnya -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <!-- Tampilan saat tidak ada jawaban untuk kategori yang dipilih -->
                                <div class="empty-state">
                                    <h5>Tidak ada jawaban untuk kategori ini.</h5>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if (!empty($answers) && count($answers) > 0)
                        <div class="card-footer text-right">
                            <nav>
                                {{ $answers->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
