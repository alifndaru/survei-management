@extends('dashboard.layouts.app')

@section('title', 'Dashboard Page')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .chart-container {
            position: relative;
            margin: 2rem auto;
            height: 60vh;
            width: 80vw;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: 12px;
            background-color: #f9fafb;
        }

        /* Custom font styling for chart */
        .chart-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            color: #2d3748;
        }

        .btn-export {
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #48bb78, #38a169);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
            border: none;
        }

        .btn-export:hover {
            background: linear-gradient(45deg, #38a169, #2f855a);
            box-shadow: 0 6px 12px rgba(56, 161, 105, 0.4);
        }

        /* Responsive chart container */
        @media (max-width: 768px) {
            .chart-container {
                width: 95vw;
                height: 50vh;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('export-user-answers') }}" class="btn-export">Export to Excel</a>

                <div class="chart-container">
                    <h2 class="chart-title">Grafik Jawaban Straus Section 1</h2>
                    <canvas id="answersChartSection1"></canvas>
                </div>

                <div class="chart-container">
                    <h2 class="chart-title">Grafik Jawaban Straus Section 2</h2>
                    <canvas id="answersChartSection2"></canvas>
                </div>

                <div class="chart-container">
                    <h2 class="chart-title">Grafik Jawaban ACP</h2>
                    <canvas id="answersChartCategory2Section1"></canvas>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartConfig = (ctx, labels, data, labelText) => {
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: labelText,
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 16
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            }
                        }
                    }
                }
            });
        };

        // Chart for Section 1
        var ctx1 = document.getElementById('answersChartSection1').getContext('2d');
        chartConfig(ctx1, {!! $labels->toJson() !!}, {!! $data->toJson() !!}, '# Jawaban Straus Section 1');

        // Chart for Section 2
        var ctx2 = document.getElementById('answersChartSection2').getContext('2d');
        chartConfig(ctx2, {!! $labelsSection2->toJson() !!}, {!! $dataSection2->toJson() !!}, '# Jawaban Straus Section 2');

        // Chart for ACP Section 1
        var ctx3 = document.getElementById('answersChartCategory2Section1').getContext('2d');
        chartConfig(ctx3, {!! $labelsAcp->toJson() !!}, {!! $dataAcp->toJson() !!}, '# Jawaban ACP Section 1');
    </script>
@endpush
