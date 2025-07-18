@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{--
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
--}}

<div class="row">
    @if (auth()->user()->role == 'admin')
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kursus</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\SwimmingCourse::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pendaftaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Registration::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pendaftaran Menunggu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Registration::where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Pengguna</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Kursus Saya</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->registrations()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kursus Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->registrations()->where('status', 'approved')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kursus Menunggu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ auth()->user()->registrations()->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Kursus Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Distribusi Kursus</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Pemula
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Menengah
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Lanjutan
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->role == 'admin')
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kursus</th>
                                <th>Tanggal Mulai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Registration::with(['user', 'swimmingCourse'])->latest()->take(5)->get() as $registration)
                            <tr>
                                <td>{{ $registration->user->name }}</td>
                                <td>{{ $registration->swimmingCourse->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($registration->start_date)) }}</td>
                                <td>
                                    @if($registration->status == 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                    @elseif($registration->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                    @elseif($registration->status == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @else
                                    <span class="badge badge-secondary">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kursus Populer</h6>
            </div>
            <div class="card-body">
                @foreach(\App\Models\SwimmingCourse::withCount('registrations')->orderBy('registrations_count', 'desc')->take(5)->get() as $course)
                <h4 class="small font-weight-bold">{{ $course->name }} <span class="float-right">{{ $course->registrations_count }} Pendaftar</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(100, $course->registrations_count * 10) }}%" aria-valuenow="{{ $course->registrations_count }}" aria-valuemin="0" aria-valuemax="10"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kursus Tersedia</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(\App\Models\SwimmingCourse::where('is_active', true)->take(3)->get() as $course)
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100"> {{-- Tambahkan h-100 untuk tinggi kartu yang sama --}}
                            <img class="card-img-top" src="{{ asset('images/' . ($course->image ?? 'pool1.1.jpg')) }}" alt="{{ $course->name }}">
                            <div class="card-body d-flex flex-column"> {{-- Gunakan flex-column untuk tata letak yang lebih baik --}}
                                <h5 class="card-title text-primary font-weight-bold">{{ $course->name }}</h5>
                                <p class="card-text text-secondary">{{ \Illuminate\Support\Str::limit($course->description, 100) }}</p>
                                <div class="mt-auto"> {{-- Dorong elemen di bawah ke bagian bawah kartu --}}
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge badge-info">{{ $course->level }}</span>
                                        <span class="text-success font-weight-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('register-course') }}" class="btn btn-primary btn-block">Daftar Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>

<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // * example: number_format(1234.56, 2, ',', ' ');
  // * return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Pendaftaran",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            // Data dummy. Anda perlu mengganti ini dengan data aktual dari database.
            // Misalnya, Anda bisa meneruskan data ini dari controller ke view.
            data: [0, 10, 5, 15, 10, 20, 15, 25, 20, 30, 25, 40],
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return value; // Bisa ditambahkan '$' atau 'Pendaftar' jika perlu
                    }
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                }
            }
        }
    }
});

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["Pemula", "Menengah", "Lanjutan"],
        datasets: [{
            data: [55, 30, 15], // Data dummy. Ganti dengan data aktual dari database.
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var dataLabel = chart.labels[tooltipItem.index];
                    var value = chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    return dataLabel + ': ' + number_format(value) + '%'; // Menambahkan '%'
                }
            }
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    },
});
</script>
@endpush