<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 25mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; }
        .header { background-color: #FFD700; color: #000; padding: 12px; text-align: center; border: 2px solid #000; margin-bottom: 15px; }
        .header img { height: 80px; max-width: 200px; object-fit: contain; }
        .header h1 { margin: 5px 0; font-size: 18pt; font-weight: bold; }
        .header p { margin: 3px 0; font-size: 10pt; }
        .title-section { text-align: center; margin: 20px 0 15px; }
        .title-section h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .title-section p { margin: 5px 0; font-size: 11pt; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 8px; font-size: 10pt; vertical-align: top; }
        th { background-color: #FFD700; color: #000; font-weight: bold; }
        .no-border { border: none !important; }
        .right { text-align: right; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .sub-table { margin: 5px 0; width: 100%; }
        .sub-table td { border: none; padding: 4px; }
        .section-title { font-size: 13pt; margin: 15px 0 10px; font-weight: bold; }
        .signature { margin-top: 50px; text-align: right; font-size: 11pt; }
        .signature-space { height: 80px; }
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
    <table style="margin-bottom: 15px;">
        <tr>
            <td class="no-border" style="width: 15%;">Nomor</td>
            <td class="no-border" style="width: 35%;">: {{ $nomor_surat }}</td>
            <td class="no-border" style="width: 20%;">Tanggal Terbit</td>
            <td class="no-border" style="width: 30%;">: {{ \Carbon\Carbon::parse($tanggal_terbit)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="no-border">Perihal</td>
            <td class="no-border">: Buku Besar</td>
            <td class="no-border">Periode</td>
            <td class="no-border">: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</td>
        </tr>
    </table>

    {{-- JUDUL --}}
    <div class="title-section">
        <h2>Buku Besar</h2>
        <p>Ikhtisar Transaksi Keuangan Perusahaan</p>
    </div>

    {{-- AKTIVA --}}
    <div class="section-title">Aktiva</div>
    <table>
        <tr><th colspan="2">Aktiva Lancar</th></tr>
        @foreach($aktivaLancar as $desc => $amount)
            <tr><td>{{ $desc }}</td><td class="right">{{ number_format($amount, 2, ',', '.') }}</td></tr>
        @endforeach
        <tr><th colspan="2">Aktiva Tetap</th></tr>
        @foreach($aktivaTetap as $desc => $amount)
            <tr><td>{{ $desc }}</td><td class="right">{{ number_format($amount, 2, ',', '.') }}</td></tr>
        @endforeach
        <tr><td class="bold">Total Aktiva</td><td class="right bold">{{ number_format($totalAktiva, 2, ',', '.') }}</td></tr>
    </table>

    {{-- KEWAJIBAN DAN EKUITAS --}}
    <div class="section-title">Kewajiban dan Ekuitas</div>
    <table>
        <tr><th colspan="2">Kewajiban Jangka Pendek</th></tr>
        @foreach($kewajibanPendek as $desc => $amount)
            <tr><td>{{ $desc }}</td><td class="right">{{ number_format($amount, 2, ',', '.') }}</td></tr>
        @endforeach
        <tr><th colspan="2">Kewajiban Jangka Panjang</th></tr>
        @foreach($kewajibanPanjang as $desc => $amount)
            <tr><td>{{ $desc }}</td><td class="right">{{ number_format($amount, 2, ',', '.') }}</td></tr>
        @endforeach
        <tr><td class="bold">Total Kewajiban</td><td class="right bold">{{ number_format($totalKewajiban, 2, ',', '.') }}</td></tr>
        <tr><td class="bold">Ekuitas</td><td class="right bold">{{ number_format($ekuitas, 2, ',', '.') }}</td></tr>
    </table>

    {{-- LABA RUGI --}}
    <div class="section-title">Laporan Laba Rugi</div>
    <table>
        <tr><th>Deskripsi</th><th class="right">Jumlah (Rp)</th></tr>
        <tr><td>Pendapatan</td><td class="right">{{ number_format($totalIncome, 2, ',', '.') }}</td></tr>
        <tr><td>Harga Pokok Penjualan (HPP)</td><td class="right">{{ number_format($hpp, 2, ',', '.') }}</td></tr>
        <tr><td class="bold">Laba Kotor</td><td class="right bold">{{ number_format($labaKotor, 2, ',', '.') }}</td></tr>
        <tr><td>Beban Usaha</td><td class="right">{{ number_format($bebanUsaha, 2, ',', '.') }}</td></tr>
        <tr><td class="bold">Laba Bersih</td><td class="right bold">{{ number_format($labaBersih, 2, ',', '.') }}</td></tr>
    </table>

    {{-- PAJAK --}}
    <div class="section-title">Laporan Pajak</div>
    <table>
        <tr><th>Jenis Pajak</th><th class="right">Jumlah (Rp)</th></tr>
        <tr><td>PPN (11%)</td><td class="right">{{ number_format($ppn, 2, ',', '.') }}</td></tr>
        <tr><td>PPh Pasal 21</td><td class="right">{{ number_format($pph21, 2, ',', '.') }}</td></tr>
        <tr><td>PPh Badan (22%)</td><td class="right">{{ number_format($pphBadan, 2, ',', '.') }}</td></tr>
        <tr><td class="bold">Total SPT Tahunan</td><td class="right bold">{{ number_format($totalSPT, 2, ',', '.') }}</td></tr>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <p>Grobogan, {{ \Carbon\Carbon::parse($tanggal_terbit)->format('d F Y') }}</p>
        <p><strong>Direktur</strong></p>
        <div class="signature-space"></div>
        <p><u>{{ $company['director_name'] ?? 'Andi Pratama' }}</u></p>
    </div>
</body>
</html>