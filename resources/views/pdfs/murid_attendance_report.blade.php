<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        /* Gaya dasar untuk PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Gunakan font yang mendukung karakter unicode jika ada */
            font-size: 10pt;
            margin: 40px;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'DejaVu Sans', sans-serif;
            margin-top: 0;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 50px;
        }
        .summary {
            margin-top: 20px;
            border: 1px solid #eee;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $reportTitle }}</h2>
        <p>{{ $periodText }}</p>
        <p>Dicetak oleh: {{ $muridName }} pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    @if($attendances->isEmpty())
        <p style="text-align: center;">Tidak ada data absensi untuk periode ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Waktu Les</th>
                    <th>Kursus</th>
                    <th>Lokasi</th>
                    <th>Guru</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->schedule->start_time_of_day)->format('H:i') }} - {{ \Carbon\Carbon::parse($attendance->schedule->end_time_of_day)->format('H:i') }}</td>
                        <td>{{ $attendance->schedule->swimmingCourse->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->schedule->location->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->schedule->guru->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($attendance->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Ringkasan Absensi</h3>
            <p><strong>Total Absensi Dicatat:</strong> {{ $summary['total_absensi'] }}</p>
            <p><strong>Total Hadir:</strong> {{ $summary['total_hadir'] }}</p>
            <p><strong>Total Alpha:</strong> {{ $summary['total_alpha'] }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Butterfly Swimming Course.</p>
    </div>
</body>
</html>
