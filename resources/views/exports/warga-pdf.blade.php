<!DOCTYPE html>
<html>
<head>
    <title>Data Warga</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Warga RT {{ auth()->user()->rt->rt ?? '-' }} / RW {{ auth()->user()->rt->rw ?? '-' }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>No KK</th>
                <th>Nama Lengkap</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wargas as $index => $warga)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $warga->nik }}</td>
                <td>{{ $warga->no_kk }}</td>
                <td>{{ $warga->name }}</td>
                <td>{{ $warga->status_domisili }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
