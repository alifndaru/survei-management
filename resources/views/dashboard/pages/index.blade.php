@extends('dashboard.layouts.app')

@section('title', 'Chart Management')

@push('style')
    <!-- Include any CSS libraries needed for dropdown styling -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Chart Management</h1>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Select Category</h4>
                        <!-- Dropdown to select category -->
                        <select id="categoryDropdown" class="form-control">
                            <option selected disabled>Select Category</option>
                            <option value="1">Straus</option>
                            <option value="2">ACP</option>
                            <option value="3">Skala Stress</option>
                        </select>

                        <!-- Dropdown to select section, initially hidden -->
                        <select id="sectionDropdown" class="form-control mt-2" style="display: none;">
                            <option value="1">Section 1</option>
                            <option value="2">Section 2</option>
                        </select>
                    </div>

                    <div class="card-body">
                        <!-- Button to download Excel, initially hidden -->
                        <div id="exportButtonContainer" class="mb-2" style="display: none;">
                            <a id="exportButton" href="#" class="btn btn-primary">Export to Excel</a>
                        </div>

                        <!-- Placeholder for Chart and message -->
                        <div id="chartContainer" style="display: none;">
                            <canvas id="chartCanvas"></canvas>
                        </div>
                        <div id="message" class="alert alert-info" style="display: block;">
                            Please select a category to display the chart.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let labels = @json($labels);
        let data = @json($data);
        let labelsSection2 = @json($labelsSection2);
        let dataSection2 = @json($dataSection2);
        let labelsAcp = @json($labelsAcp);
        let dataAcp = @json($dataAcp);
        let dataSkala = @json($dataSkala);
        let labelsSkala = @json($labelsSkala);
        let chart;
        const chartContainer = document.getElementById('chartContainer');
        const message = document.getElementById('message');
        const exportButtonContainer = document.getElementById('exportButtonContainer');
        const exportButton = document.getElementById('exportButton');

        // Function to render chart
        function renderChart(labels, data) {
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            if (chart) {
                chart.destroy(); // Destroy previous chart instance if it exists
            }
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Answers',
                        data: data,
                        backgroundColor: generateColors(data.length),
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Function to generate random colors
        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(
                    `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`
                );
            }
            return colors;
        }

        // Function to update chart and button visibility based on dropdown selections
        function updateChart() {
            const category = document.getElementById('categoryDropdown').value;
            const section = document.getElementById('sectionDropdown').value;

            let newLabels, newData;
            if (category == 1) {
                if (section == 1) {
                    newLabels = labels;
                    newData = data;
                } else if (section == 2) {
                    newLabels = labelsSection2;
                    newData = dataSection2;
                }
                exportButtonContainer.style.display = 'block'; // Show export button for category 1
                exportButton.href = "{{ route('export-user-answers-straus') }}"; // Update export button route for Straus
            } else if (category == 2) {
                newLabels = labelsAcp;
                newData = dataAcp;
                exportButtonContainer.style.display = 'block'; // Show export button for category 2 (ACP)
                exportButton.href = "{{ route('export-user-answers-acp') }}"; // Update export button route for ACP
            } else if (category == 3) {
                newLabels = labelsSkala;
                newData = dataSkala;
                exportButtonContainer.style.display = 'block'; // Show export button for category 2 (ACP)
                exportButton.href = "{{ route('export-user-answers-skala') }}"; // Update export button route for ACP

            } else {
                // Default: No data
                newLabels = [];
                newData = [];
                exportButtonContainer.style.display = 'none'; // Hide export button when no category is selected
            }

            if (newLabels.length > 0 && newData.length > 0) {
                chartContainer.style.display = 'block';
                message.style.display = 'none';
                renderChart(newLabels, newData);
            } else {
                chartContainer.style.display = 'none';
                message.style.display = 'block';
            }
        }

        // Render the initial chart on page load
        updateChart();

        // Event listener for category selection
        document.getElementById('categoryDropdown').addEventListener('change', function() {
            const category = this.value;
            if (category == 1) {
                // Show section dropdown for category 1 (Straus)
                document.getElementById('sectionDropdown').style.display = 'block';
            } else {
                // Hide section dropdown for other categories
                document.getElementById('sectionDropdown').style.display = 'none';
            }
            updateChart();
        });

        // Event listener for section selection
        document.getElementById('sectionDropdown').addEventListener('change', function() {
            updateChart();
        });
    </script>
@endpush
