<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; }

        /* tambahan biar link biru */
        a {
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Laporan Arsip</h2>

<table>
    <thead>
        <tr>
            <th>Judul</th>
            <th>Nomor</th>
            <th>Tahun</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Download</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <td>{{ $item['judul'] }}</td>
            <td>{{ $item['nomor'] }}</td>
            <td>{{ $item['tahun'] }}</td>
            <td>{{ $item['kategori'] }}</td>
            <td>{{ $item['status'] }}</td>
            <td>
                <a href="{{ $item['download_url'] }}" target="_blank">
                    {{ $item['download_url'] }}
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>