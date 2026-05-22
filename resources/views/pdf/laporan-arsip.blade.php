<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip</title>
    <style>
        th {
    background: #2f6f7e;
    color: white;
}
        body {
            font-family: sans-serif;
            margin: 30px;
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

        .tanggal {
            margin-bottom: 15px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            font-size: 11px;
        }

        a {
            color: blue;
            text-decoration: underline;
        }

        th {
            background-color: #000000 !important;
            color: white !important;
        }

        .year-header {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 12px;
            padding: 10px;
            text-align: center;
            margin-top: 15px;
            margin-bottom: 5px;
            border: 1px solid #d1d5db;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop-container">

        <table class="kop-table">
            <tr>

                <!-- LOGO -->
                <td width="15%">
                    <img 
                        src="{{ public_path('image/logodinsos.png') }}" 
                        class="logo"
                    >
                </td>

                <!-- IDENTITAS -->
                <td class="judul-dinas">
                    <h2>PEMERINTAH KABUPATEN BOYOLALI</h2>
                    <h3>DINAS SOSIAL</h3>
                    <p>Jl. Contoh Alamat No. 123</p>
                    <p>Email: dinsos@example.go.id</p>
                </td>

            </tr>
        </table>

        <div class="garis"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA ARSIP</h3>
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Nomor</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Jenis</th>
            </tr>
        </thead>

        <tbody>
            @php
                $no = 1;
                $yearGroups = $data instanceof \Illuminate\Support\Collection ? $data : collect($data);
            @endphp

            @forelse ($yearGroups as $tahun => $items)
                <tr>
                    <td colspan="6" class="year-header">Tahun {{ $tahun }}</td>
                </tr>

                @foreach ($items as $item)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $item['judul'] }}</td>
                        <td>{{ $item['nomor'] }}</td>
                        <td>{{ $item['tahun'] }}</td>
                        <td>{{ $item['kategori'] }}</td>
                        <td>{{ $item['jenis_arsip'] }}</td>
                    </tr>
                    @php $no++; @endphp
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data arsip</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>