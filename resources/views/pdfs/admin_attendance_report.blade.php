<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        /* Basic styling for PDF report */
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback for PDF generation */
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .summary {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .summary h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .summary p {
            margin: 2px 0;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 9px;
            color: #888;
        }
        .filters {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .filters strong {
            display: block;
            margin-bottom: 5px;
        }
        .filters ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .filters li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $reportTitle }}</h2>
        <p>{{ $periodText }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    @if (!empty($filtersApplied))
        <div class="filters">
            <strong>Filter Diterapkan:</strong>
            <ul>
                @foreach ($filtersApplied as $filter)
                    <li>- {{ $filter }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($attendances->isEmpty())
        <p style="text-align: center;">Tidak ada data absensi yang ditemukan untuk periode dan filter ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Kursus</th>
                    <th>Lokasi</th>
                    <th>Guru</th>
                    <th>Murid</th>
                    <th>Status</th>
                    <th>Jarak (m)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->attended_at)->format('H:i') }}</td>
                        <td>{{ $attendance->schedule->swimmingCourse->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->schedule->location->name ?? 'N/A' }}</td>
                        <td>{{ $attendance->schedule->guru->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($attendance->status) }}</td>
                        <td>{{ number_format($attendance->distance_from_course, 0) }}</td>
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
