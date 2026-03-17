<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} - Rekap Zakat</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1, h2 {
            margin: 0;
        }

        .header {
            margin-bottom: 18px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary th,
        .summary td,
        .records th,
        .records td {
            border: 1px solid #d5d5d5;
            padding: 6px;
            text-align: left;
        }

        .summary th,
        .records th {
            background: #f3f3f3;
        }

        .records {
            width: 100%;
            border-collapse: collapse;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $project->title }}</h1>
        <p>Rekapitulasi project zakat - dibuat pada {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <h2>Ringkasan</h2>
    <table class="summary">
        <tbody>
            <tr>
                <th>Total Pendaftar</th>
                <td class="text-right">{{ number_format($summary['total_list_count']) }}</td>
                <th>Total Muzakki</th>
                <td class="text-right">{{ number_format($summary['total_people']) }}</td>
            </tr>
            <tr>
                <th>Total Fitrah Beras (Kg)</th>
                <td class="text-right">{{ number_format($summary['total_rice_kg'], 2, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <th>Total Fitrah (Uang)</th>
                <td class="text-right">Rp {{ number_format($summary['total_fitrah_money'], 0, ',', '.') }}</td>
                <th>Total Wajib / Infaq</th>
                <td class="text-right">Rp {{ number_format($summary['total_wajib_money'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Zakat Mal</th>
                <td class="text-right">Rp {{ number_format($summary['total_mal_money'], 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <h2>Daftar Orang Sudah Zakat</h2>
    <table class="records">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Orang</th>
                <th>Metode</th>
                <th>Beras (Kg)</th>
                <th>Total Fitrah (Uang)</th>
                <th>Total Wajib / Infaq</th>
                <th>Total Zakat Mal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($project->zakatRecords as $record)
                <tr>
                    <td class="text-right">{{ $loop->iteration }}</td>
                    <td>{{ $record->name }}</td>
                    <td class="text-right">{{ number_format($record->people_count) }}</td>
                    <td>{{ $record->method === 'rice' ? 'Beras' : 'Tunai' }}</td>
                    <td class="text-right">{{ $record->rice_kg !== null ? number_format((float) $record->rice_kg, 2, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $record->fitrah_money !== null ? 'Rp '.number_format((float) $record->fitrah_money, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format((float) $record->wajib_money, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) $record->mal_money, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-right">Belum ada data orang zakat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
