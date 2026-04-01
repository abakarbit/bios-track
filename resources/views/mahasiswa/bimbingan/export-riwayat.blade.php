<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Kendali Bimbingan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 3px solid #000;
            padding: 10px;
        }
        .header-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .header-subtitle {
            font-size: 11px;
            margin-bottom: 2px;
        }
        .header-address {
            font-size: 10px;
            margin-bottom: 2px;
        }
        .title-card {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 12px 0;
            text-decoration: underline;
        }
        .info-section {
            margin-bottom: 12px;
            font-size: 11px;
        }
        .info-row {
            display: flex;
            margin-bottom: 4px;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        .current-stage {
            background: #f5f5f5;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }
        td {
            height: 25px;
        }
        .no-data {
            text-align: center;
            color: #999;
            padding: 20px !important;
        }
        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
        }
        .signature-box {
            width: 150px;
        }
        .signature-space {
            height: 40px;
            border-bottom: 1px solid #000;
        }
        .signature-name {
            margin-top: 8px;
            font-weight: bold;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* ======================================== */
        /* PRINT STYLES - Agar sesuai ukuran kertas */
        /* ======================================== */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                height: auto;
                margin: 0;
                page-break-after: always;
                page-break-inside: avoid;
                box-shadow: none;
                border: none;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            .signature-section {
                page-break-inside: avoid;
            }

            /* Pastikan background tercetak */
            th, .current-stage {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="page">

        <div style="padding: 10mm;">
        <!-- Header -->
        <!-- image -->

        <div class="header">

            <div class="header-title">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
            <div class="header-subtitle">INSTITUT TEKNOLOGI SUMATERA</div>
            <div class="header-subtitle">JURUSAN TEKNIK PRODUKSI DAN INDUSTRI</div>
            <div class="header-address">Jalan Terusan Ryacudu Way Hiu, Kecamatan Jati Agung, Lampung Selatan 35365</div>
            <div class="header-address">Telepon: (0721) 8030188 | Email: tjpi@itera.ac.id | Website: http://tjpi.ac.id</div>
            <div style="text-align: left; position: absolute; top: 12mm; left: 10mm;">
                <img src="{{ public_path('storage/images/ITERA.png') }}" alt="Logo ITERA" style="height: 100px;">
            </div>
        </div>

        <!-- Title -->
        <div style="text-align: center; font-size: 11px; margin-bottom: 12px; font-weight: bold;">KARTU KENDALI BIMBINGAN PENELITIAN TUGAS AKHIR <br> PROGRAM STUDI TEKNIK BIOSISTEM <br> JURUSAN TEKNIK PRODUKSI DAN INDUSTRI</div>


        <!-- Student Info -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Nama : {{ $user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIM : {{ $user->nim }}</div>
            </div>
        </div>


        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 35%">Tahapan Bimbingan</th>
                    <th style="width: 25%">Dosen Pembimbing</th>
                    <th style="width: 10%">Paraf Mahasiswa</th>
                    <th style="width: 10%">Paraf Dosen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressRecords as $index => $record)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td style="text-align: center">{{ $record->tanggal_bimbingan->format('d M Y H:i') }}</td>
                    <td>{{ Str::limit($record->catatan, 50) }}</td>
                    <td>{{ $record->bimbingan->dosen->name ?? '-' }}</td>
                    <td style="text-align: center">
                        @if($user->signature_path)
                            <img src="{{ public_path('storage/' . $user->signature_path) }}" alt="Signature" style="max-height: 25px; max-width: 80px;">
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if($record->status === 'disetujui' && $record->bimbingan->dosen->signature_path)
                            <img src="{{ public_path('storage/' . $record->bimbingan->dosen->signature_path) }}" alt="Dosen Signature" style="max-height: 25px; max-width: 80px;">
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">Belum ada data bimbingan</td>
                </tr>
                @endforelse

                <!-- Empty rows for additional entries -->
                @for($i = count($progressRecords); $i < 10; $i++)
                <tr>
                    <td style="text-align: center">{{ $i + 1 }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <!-- Signature Section -->
        {{-- <div class="signature-section">
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 20px;">Mahasiswa</div>
                @if($user->signature_path)
                    <div style="border-bottom: 1px solid #000; padding: 10px 0; text-align: center;">
                        <img src="{{ public_path('storage/' . $user->signature_path) }}" alt="Signature" style="max-height: 50px; max-width: 100px; display: inline-block;">
                    </div>
                @else
                    <div class="signature-space"></div>
                @endif
                <div class="signature-name">{{ $user->name }}</div>
                <div>{{ $user->nim }}</div>
            </div>
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 20px;">Pembimbing 1</div>
                <div class="signature-space"></div>
                <div class="signature-name">(..................)</div>
            </div>
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 20px;">Pembimbing 2</div>
                <div class="signature-space"></div>
                <div class="signature-name">(..................)</div>
            </div>
        </div> --}}

        {{-- <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #666;">
            <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        </div> --}}
        </div>
    </div>
</body>
</html>
