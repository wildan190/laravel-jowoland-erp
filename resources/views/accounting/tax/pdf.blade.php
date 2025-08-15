<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
        .header { background-color: #FFD700; color: #000; padding: 10px; text-align: center; border: 2px solid #000; }
        .header img { height: 70px; }
        .header h1 { margin: 0; font-size: 18px; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 11px; }
        .title-section { text-align: center; margin: 20px 0 10px; }
        .title-section h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .title-section p { margin: 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 11px; }
        th { background-color: #FFD700; color: #000; text-align: left; }
        .no-border { border: none !important; }
        .right { text-align: right; }
        .center { text-align: center; }
        .signature { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>

    {{-- HEADER / KOP SURAT --}}
    <div class="header">
        @if($company['logo'])
            <img src="{{ $company['logo'] }}" alt="Logo Perusahaan"><br>
        @endif
        <h1>{{ $company['name'] }}</h1>
        <p>{{ $company['address'] }}</p>
        <p>Telp: {{ $company['phone'] }} | Email: {{ $company['email'] }}</p>
        <p>NPWP: {{ $company['npwp'] }}</p>
    </div>

    {{-- INFORMASI SURAT --}}
    <table style="margin-top:10px;">
        <tr>
            <td class="no-border">Nomor</td>
            <td class="no-border">: {{ $nomor_surat }}</td>
            <td class="no-border">Tanggal Terbit</td>
            <td class="no-border">: {{ $tanggal_terbit }}</td>
        </tr>
        <tr>
            <td class="no-border">Perihal</td>
            <td class="no-border">: Laporan Pajak & Keuangan</td>
            <td class="no-border">Periode</td>
            <td class="no-border">: {{ $startDate }} s/d {{ $endDate }}</td>
        </tr>
    </table>

    {{-- JUDUL --}}
    <div class="title-section">
        <h2>Laporan Pajak & Keuangan</h2>
        <p>Disusun berdasarkan data transaksi perusahaan</p>
    </div>

    {{-- LABA RUGI --}}
    <h4>Laporan Laba Rugi</h4>
    <table>
        <tr><th>Deskripsi</th><th class="right">Jumlah (Rp)</th></tr>
        <tr><td>Total Pendapatan</td><td class="right">{{ number_format($totalIncome,0,',','.') }}</td></tr>
        <tr><td>Harga Pokok Penjualan (HPP)</td><td class="right">{{ number_format($hpp,0,',','.') }}</td></tr>
        <tr><td><strong>Laba Kotor</strong></td><td class="right"><strong>{{ number_format($labaKotor,0,',','.') }}</strong></td></tr>
        <tr><td>Beban Usaha</td><td class="right">{{ number_format($bebanUsaha,0,',','.') }}</td></tr>
        <tr><td><strong>Laba Bersih</strong></td><td class="right"><strong>{{ number_format($labaBersih,0,',','.') }}</strong></td></tr>
    </table>

    {{-- PAJAK --}}
    <h4>Laporan Pajak</h4>
    <table>
        <tr><th>Jenis Pajak</th><th class="right">Jumlah (Rp)</th></tr>
        <tr><td>PPN (11%)</td><td class="right">{{ number_format($ppn,0,',','.') }}</td></tr>
        <tr><td>PPh21</td><td class="right">{{ number_format($pph21,0,',','.') }}</td></tr>
        <tr><td>PPh Badan (22%)</td><td class="right">{{ number_format($pphBadan,0,',','.') }}</td></tr>
        <tr><td><strong>Total SPT Tahunan</strong></td><td class="right"><strong>{{ number_format($totalSPT,0,',','.') }}</strong></td></tr>
    </table>

    {{-- NERACA --}}
    <h4>Neraca</h4>
    <table>
        <tr><th>Posisi</th><th class="right">Jumlah (Rp)</th></tr>
        <tr><td>Aset</td><td class="right">{{ number_format($aset,0,',','.') }}</td></tr>
        <tr><td>Kewajiban</td><td class="right">{{ number_format($kewajiban,0,',','.') }}</td></tr>
        <tr><td><strong>Ekuitas</strong></td><td class="right"><strong>{{ number_format($ekuitas,0,',','.') }}</strong></td></tr>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        Grobogan, {{ $tanggal_terbit }}<br>
        <strong>Direktur</strong><br><br><br>
        <u>Andi Pratama</u>
    </div>

</body>
</html>
