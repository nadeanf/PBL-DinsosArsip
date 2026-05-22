<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 30px;
            color: #000000;
        }

        .kop-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .kop-table {
            width: 100%;
            border: none;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 90px;
        }

        .judul-dinas {
            text-align: center;
        }

        .judul-dinas h2,
        .judul-dinas h3,
        .judul-dinas p {
            margin: 2px;
            color: #000000;
        }

        .garis {
            border-top: 3px solid black;
            border-bottom: 1px solid black;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-laporan h3 {
            color: #000000;
        }

        .tanggal {
            margin-bottom: 15px;
            font-size: 12px;
            color: #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Warna Judul Tabel Hitam Pekat dengan Text Putih */
        th {
            background-color: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000;
            padding: 8px;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid #000000;
            padding: 8px;
            font-size: 11px;
            color: #000000;
        }

        a {
            color: #000000;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop-container">
        <table class="kop-table">
            <tr>
                <td width="15%">
                    <img src="{{ public_path('image/logodinsos.png') }}" class="logo">
                </td>
                <td class="judul-dinas">
                    <h2>PEMERINTAH KABUPATEN BOYOLALI</h2>
                    <h3>DINAS SOSIAL</h3>
                    <p>Jl. Kebo Kenongo Tegalarum, Kemiri, Mojosongo, Boyolali</p>
                    <p>Email: dinsos@boyolali.go.id</p>
                </td>
            </tr>
        </table>
        <div class="garis"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA ARSIP</h3>
    </div>

    <!-- TANGGAL -->
    <div class="tanggal">
        Tanggal Export: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Judul</th>
                <th>Nomor</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Akses</th>
                <th>Masa Aktif</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item['judul'] }}</td>
                <td style="text-align: center;">{{ $item['nomor'] }}</td>
                <td style="text-align: center;">{{ $item['tahun'] }}</td>
                <td>{{ $item['kategori'] }}</td>
                <td style="text-align: center;">{{ $item['status'] }}</td>
                <td style="text-align: center;">{{ $item['masa_aktif'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>