<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Rekap Pembayaran SPP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #1a202c;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #4a5568;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #edf2f7;
            font-weight: bold;
            border: 1px solid #cbd5e0;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }

        td {
            border: 1px solid #cbd5e0;
            padding: 8px 10px;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .badge-lunas {
            color: #065f46;
            font-weight: bold;
        }

        .badge-cicil {
            color: #92400e;
            font-weight: bold;
        }

        .badge-belum {
            color: #991b1b;
            font-weight: bold;
        }

        .class-header {
            background-color: #edf2f7;
            font-weight: bold;
            padding: 10px;
            margin-top: 20px;
            font-size: 14px;
        }

        .class-header span {
            font-weight: normal;
            font-size: 12px;
            color: #4a5568;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        .summary {
            margin: 8px 0 10px 0;
            font-size: 12px;
        }

        .summary span {
            margin-right: 20px;
        }

        .text-lunas {
            color: #065f46;
        }

        .text-cicil {
            color: #92400e;
        }

        .text-belum {
            color: #991b1b;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REKAP PEMBAYARAN SPP</h1>
        <p>Bulan: <strong>{{ $bulanIndonesia }}</strong> | Tahun Ajaran: <strong>{{ $tahunAjaran }}</strong></p>
    </div>

    @forelse($rekapData as $data)
    <div class="class-header">
        Kelas {{ $data['kelas']->nama_kelas }}
        <span>(Wali Kelas: {{ $data['kelas']->waliKelas->name ?? '-' }})</span>
    </div>
    <div class="summary">
        <span>✅ Lunas: <strong>{{ $data['total_lunas'] }}</strong></span>
        <span>🔄 Cicil: <strong>{{ $data['total_cicil'] }}</strong></span>
        <span>❌ Belum Lunas: <strong>{{ $data['total_belum_lunas'] }}</strong></span>
        <span>📊 Total Siswa: <strong>{{ $data['total_siswa'] }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 20%;">Jumlah Dibayar</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['siswa'] as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item['siswa']->nis }}</td>
                <td>{{ $item['siswa']->nama }}</td>
                <td>
                    @if($item['status'] == 'lunas')
                    <span class="badge-lunas">✅ Lunas</span>
                    @elseif($item['status'] == 'cicil')
                    <span class="badge-cicil">🔄 Cicil</span>
                    @else
                    <span class="badge-belum">❌ Belum Lunas</span>
                    @endif
                </td>
                <td>
                    @if($item['jumlah_dibayar'] > 0)
                    Rp {{ number_format($item['jumlah_dibayar'], 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $item['pembayaran'] ? $item['pembayaran']->keterangan ?? '-' : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada siswa di kelas ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    @empty
    <p style="text-align: center; color: #718096;">Tidak ada data rekap.</p>
    @endforelse

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>