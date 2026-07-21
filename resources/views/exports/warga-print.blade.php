<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Warga RT {{ auth()->user()->rt->rt }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2, .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 5px;">Cetak Halaman Ini</button>
    </div>

    <div class="header">
        <h2>DATA WARGA RT {{ auth()->user()->rt->rt ?? '...' }}</h2>
        <p>Alamat / Wilayah: {{ auth()->user()->rt->alamat ?? '...' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No KK</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>L/P</th>
                <th>Domisili</th>
                <th>Status Perkawinan</th>
                <th>SHDK</th>
                <th>Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wargas as $index => $warga)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $warga->no_kk }}</td>
                <td>{{ $warga->nik }}</td>
                <td>{{ $warga->name }}</td>
                <td>{{ $warga->jenis_kelamin }}</td>
                <td>{{ $warga->status_domisili }}</td>
                <td>{{ $warga->status_perkawinan }}</td>
                <td>{{ $warga->status_keluarga }}</td>
                <td>{{ $warga->penghasilan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
