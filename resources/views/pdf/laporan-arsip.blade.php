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
            width: 100px;
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
            border-top: 2px solid black;
            border-bottom: 1px solid black;
            height: 3px;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 10px;
        }

        .judul-laporan h3 {
            color: #000000;
        }

        .tanggal {
            margin-bottom: 10px;
            font-size: 12px;
            color: #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Warna Judul Tabel Hitam Pekat dengan Text Putih */
        th {
            background-color: #2f6f7e;
            color: white;
            border: 1px solid #5964dc;
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
                <td width="15%">
                    <img src="{{ public_path('image/logodinsos.png') }}" class="logo">
                </td>
                <td class="judul-dinas">
                    <div style="font-size: 18px;">
                        PEMERINTAH KABUPATEN BOYOLALI
                    </div>

                    <div style="font-size: 34px; font-weight: bold; margin-top: 5px;">
                        DINAS SOSIAL
                    </div>

                    <div style="font-size: 14px; margin-top: 6px; line-height: 1.5;">
                        Komplek Perkantoran Terpadu Kabupaten Boyolali<br>
                        Jalan : Kebo Kenongo, (0276) 321 021 / 321 047, Faks 321 098, Kemiri<br>
                        Boyolali 57321, Provinsi Jawa Tengah
                    </div>

                    <div style="font-size: 14px; margin-top: 5px;">
                        <i>Email : dinsos@boyolali.go.id</i>
                    </div>
                </td>
            </tr>
        </table>
        <div class="garis"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA ARSIP</h3>
    </div>

    <!-- TANGGAL CETAK -->
    <div class="tanggal">
        Dicetak pada: {{ now()->format('d-m-Y') }}
    </div>

    <!-- FILTER INFO -->
    @if(!empty($filter))
        <div class="tanggal">
            @if(isset($filter['search']) && $filter['search'])
                <div>Keyword: {{ $filter['search'] }}</div>
            @endif

            @if(isset($filter['kategori']) && $filter['kategori'])
                <div>Kategori: {{ $filter['kategori'] }}</div>
            @endif

            @if(isset($filter['tanggal_awal']) && $filter['tanggal_awal'])
                <div>
                    Periode: 
                    {{ $filter['tanggal_awal'] }} 
                    s/d 
                    {{ $filter['tanggal_akhir'] ?? 'Sekarang' }}
                </div>
            @endif
        </div>
    @endif

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Judul</th>
                <th>Nomor</th>
                <th>Tahun</th>
                <th>Kategori</th>
                <th>Jenis</th>
            </tr>
        </thead>

        <tbody>
            @php
                $yearGroups = $arsip instanceof \Illuminate\Support\Collection ? $arsip : collect($arsip);
            @endphp

            @forelse ($yearGroups as $tahun => $items)

                <!-- HEADER TAHUN -->
                <tr>
                    <td colspan="6" class="year-header">Tahun {{ $tahun }}</td>
                </tr>

                @php $no = 1; @endphp

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
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        Tidak ada data arsip
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <br><br>

    <table width="100%" style="border: none;">
    <tr>
        <td style="border: none; text-align: right;">
            Boyolali, {{ now()->format('d-m-Y') }}<br><br><br><br>
            <b>Kepala Dinas</b>
        </td>
    </tr>
</table>

</body>
</html>