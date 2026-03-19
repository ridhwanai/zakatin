<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} - Rekap Zakat</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
            color: #1b2b24;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 12px;
        }

        td,
        th {
            border: 1px solid #d4e1db;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .title-cell {
            background: #1f5b45;
            color: #ffffff;
            font-size: 15pt;
            font-weight: 700;
            text-align: center;
            padding: 10px 8px;
        }

        .section-cell {
            background: #2b7a5a;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
        }

        .summary-label {
            font-weight: 700;
            color: #1f5b45;
        }

        .table-head th {
            background: #1f5b45;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }

        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .row-even td { background: #f6fbf8; }

        .num { mso-number-format: "#,##0"; }
        .dec { mso-number-format: "#,##0.00"; }
        .rp  { mso-number-format: "\0022Rp\0022 #,##0"; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="8" class="title-cell">REKAP ZAKAT - {{ $project->title }}</td>
        </tr>
        <tr>
            <td class="summary-label">Tanggal Export</td>
            <td colspan="7">{{ $exportedAt }}</td>
        </tr>
        <tr>
            <td colspan="8" class="section-cell">RINGKASAN</td>
        </tr>
        <tr>
            <td class="summary-label">Total Pendaftar</td>
            <td class="text-right num">{{ (int) $summary['total_list_count'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Pendaftar (Beras)</td>
            <td class="text-right num">{{ (int) $summary['total_list_count_rice'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Pendaftar (Uang)</td>
            <td class="text-right num">{{ (int) $summary['total_list_count_money'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Pendaftar (Custom)</td>
            <td class="text-right num">{{ (int) $summary['total_list_count_custom'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Pendaftar (Mal > 0)</td>
            <td class="text-right num">{{ (int) $summary['total_list_count_mal'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Muzakki</td>
            <td class="text-right num">{{ (int) $summary['total_people'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Muzakki (Beras)</td>
            <td class="text-right num">{{ (int) $summary['total_people_rice'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Muzakki (Uang)</td>
            <td class="text-right num">{{ (int) $summary['total_people_money'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Muzakki (Custom)</td>
            <td class="text-right num">{{ (int) $summary['total_people_custom'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Muzakki (Mal > 0)</td>
            <td class="text-right num">{{ (int) $summary['total_people_mal'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Fitrah Beras (Kg)</td>
            <td class="text-right dec">{{ (float) $summary['total_rice_kg'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Fitrah Uang (Rp)</td>
            <td class="text-right rp">{{ (float) $summary['total_fitrah_money'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Uang Wajib / Infaq (Rp)</td>
            <td class="text-right rp">{{ (float) $summary['total_wajib_money'] }}</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="summary-label">Total Zakat Mal (Rp)</td>
            <td class="text-right rp">{{ (float) $summary['total_mal_money'] }}</td>
            <td colspan="6"></td>
        </tr>
    </table>

    <table>
        <thead class="table-head">
            <tr>
                <th style="width: 7%;">NO</th>
                <th style="width: 28%;">NAMA</th>
                <th style="width: 10%;">ORANG</th>
                <th style="width: 12%;">METODE</th>
                <th style="width: 13%;">FITRAH BERAS (KG)</th>
                <th style="width: 13%;">FITRAH UANG (RP)</th>
                <th style="width: 9%;">WAJIB / INFAQ (RP)</th>
                <th style="width: 8%;">ZAKAT MAL (RP)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($project->zakatRecords as $record)
                <tr class="{{ $loop->even ? 'row-even' : '' }}">
                    <td class="text-center num">{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $record->name }}</td>
                    <td class="text-center num">{{ (int) $record->people_count }}</td>
                    <td class="text-center">
                        {{
                            $record->method === 'rice'
                                ? 'Beras'
                                : ($record->method === 'money' ? 'Tunai' : 'Custom')
                        }}
                    </td>
                    <td class="text-right dec">{{ $record->rice_kg !== null ? (float) $record->rice_kg : '' }}</td>
                    <td class="text-right rp">{{ $record->fitrah_money !== null ? (float) $record->fitrah_money : '' }}</td>
                    <td class="text-right rp">{{ (float) $record->wajib_money }}</td>
                    <td class="text-right rp">{{ (float) $record->mal_money }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center">-</td>
                    <td colspan="7" class="text-left">Belum ada data orang zakat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
