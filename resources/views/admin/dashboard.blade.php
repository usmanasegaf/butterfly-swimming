{{-- filepath: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Guru</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalGuru }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Murid</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMurid }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Kursus</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKursus }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pemasukan</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Murid Baru per Bulan ({{ date('Y') }})</h6>
        </div>
        <div class="card-body">
            <canvas id="muridChart"></canvas>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('muridChart').getContext('2d');
            const muridChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ],
                    datasets: [{
                        label: 'Murid Baru',
                        data: [
                            @for ($i = 1; $i <= 12; $i++)
                                {{ $muridPerBulan[$i] ?? 0 }}{{ $i < 12 ? ',' : '' }}
                            @endfor
                        ],
                        backgroundColor: 'rgba(78, 115, 223, 0.5)',
                        borderColor: 'rgba(78, 115, 223, 1)',
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
        </script>
    @endpush
@endsection
