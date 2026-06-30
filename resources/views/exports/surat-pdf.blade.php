<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar</title>
    <style>
        body { font-family: sans-serif; line-height: 1.5; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .header h3, .header h4, .header p { margin: 0; }
        .content { margin-bottom: 30px; }
        .table-data { width: 100%; margin-bottom: 20px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .table-data td:first-child { width: 150px; }
        .footer { width: 100%; margin-top: 50px; }
        .footer-table { width: 100%; }
        .footer-table td { width: 50%; text-align: center; }
        .signature { margin-top: 60px; font-weight: bold; text-decoration: underline; }
        .verify-text { font-size: 10px; color: gray; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>RUKUN TETANGGA (RT) {{ $surat->rt->rt }} / RUKUN WARGA (RW) {{ $surat->rt->rw }}</h3>
        <h4>KELURAHAN {{ strtoupper($surat->rt->village->name ?? '') }}</h4>
        <p>Alamat: {{ $surat->rt->village->name ?? '' }}, Kecamatan, Kota/Kabupaten</p>
    </div>
    
    <div style="text-align: center; margin-bottom: 20px;">
        <h4 style="text-decoration: underline; margin:0;">SURAT {{ strtoupper(str_replace('_', ' ', $surat->jenis)) }}</h4>
        <p style="margin:0;">Nomor: {{ $surat->nomor_surat }}</p>
    </div>
    
    <div class="content">
        <p>Yang bertanda tangan di bawah ini Ketua RT {{ $surat->rt->rt }} / RW {{ $surat->rt->rw }}, Kelurahan {{ $surat->rt->village->name ?? '' }}, menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="table-data">
            <tr><td>Nama Lengkap</td><td>:</td><td><b>{{ $surat->warga->name }}</b></td></tr>
            <tr><td>NIK</td><td>:</td><td>{{ $surat->warga->nik }}</td></tr>
            <tr><td>Nomor KK</td><td>:</td><td>{{ $surat->warga->no_kk }}</td></tr>
            <tr><td>Status Domisili</td><td>:</td><td>{{ $surat->warga->status_domisili }}</td></tr>
        </table>
        
        <p>Orang tersebut di atas adalah benar warga kami dan bertempat tinggal di lingkungan RT {{ $surat->rt->rt }} / RW {{ $surat->rt->rw }}. Surat ini dibuat untuk keperluan:</p>
        <p><b>"{{ $surat->keperluan }}"</b></p>
        
        <p>Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>
    </div>
    
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td></td>
                <td>
                    Dikeluarkan di: Tempat<br>
                    Pada tanggal: {{ \Carbon\Carbon::parse($surat->signed_at)->format('d F Y') }}<br><br><br>
                    <div style="font-size: 12px; color: green; margin: 10px 0;">[Telah Ditandatangani Secara Digital]</div>
                    <div class="signature">Ketua RT {{ $surat->rt->rt }}</div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="verify-text">
        Dokumen ini dibuat otomatis oleh Sistem LaporPakRT. Verifikasi keaslian dokumen dapat dilakukan di: {{ url('/verifikasi/' . $surat->qrcode_token) }}
    </div>
</body>
</html>
