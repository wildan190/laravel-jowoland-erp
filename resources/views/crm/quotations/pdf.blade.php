<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header .logo {
            display: table-cell;
            width: 25%;
            vertical-align: middle;
        }

        .header .logo img {
            max-height: 70px;
        }

        .header .company-info {
            display: table-cell;
            width: 75%;
            vertical-align: middle;
            text-align: right;
        }

        .header .company-info h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .header .company-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #333;
        }

        /* QUOTATION INFO */
        .quotation-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .quotation-info .from,
        .quotation-info .to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .quotation-info h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #FFD700;
        }

        .quotation-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #333;
        }

        /* TITLE */
        .quotation-title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 700;
            background: linear-gradient(180deg, #FFD700, #FFC107);
            padding: 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background-color: #FFD700;
            text-align: left;
            font-weight: 600;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .right {
            text-align: right;
        }

        tfoot td {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        /* SIGNATURE */
        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }

        /* FOOTER */
        .footer {
            border-top: 2px solid #FFD700;
            margin-top: 30px;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
            color: #333;
        }

        /* PAGE BREAK */
        .page-break {
            page-break-before: always;
        }

        /* TERMS AND CONDITIONS */
        .terms-container {
            margin-top: 20px;
        }

        .terms-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .terms-section {
            margin-bottom: 15px;
        }

        .terms-section h4 {
            font-size: 12px;
            font-weight: 600;
            margin: 5px 0;
        }

        .terms-section p,
        .terms-section ul {
            margin: 3px 0;
            font-size: 11px;
        }

        .terms-section ul {
            padding-left: 15px;
        }

        .terms-section li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    {{-- Halaman 1: Quotation --}}
    {{-- HEADER --}}
    <div class="header">
        <div class="logo">
            @php
                $logoPath = public_path('assets/img/logo.png');
                $logoBase64 = file_exists($logoPath)
                    ? 'data:image/' .
                        pathinfo($logoPath, PATHINFO_EXTENSION) .
                        ';base64,' .
                        base64_encode(file_get_contents($logoPath))
                    : null;
            @endphp
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo Perusahaan">
            @endif
        </div>
        <div class="company-info">
            <h1>PT. Jowoland Construction</h1>
            <p>Ketitang, Godong, Grobogan, Jawa Tengah</p>
            <p>Telp: 0852-8074-9218 | Email: info@jowolandborepile.com</p>
            <p>NPWP: 01.234.567.8-999.000</p>
        </div>
    </div>

    {{-- QUOTATION INFO --}}
    <div class="quotation-info">
        <div class="from">
            <h3>Dari:</h3>
            <p><strong>PT. Jowoland Construction</strong></p>
            <p>Ketitang, Godong, Grobogan, Jawa Tengah</p>
            <p>Telp: 0852-8074-9218</p>
            <p>Email: info@jowolandborepile.com</p>
        </div>
        <div class="to">
            <h3>Kepada:</h3>
            <p><strong>{{ $quotation->contact->name }}</strong></p>
            <p>{{ $quotation->contact->company ?? '-' }}</p>
            <p>{{ $quotation->contact->address ?? '-' }}</p>
            <p>Telp: {{ $quotation->contact->phone ?? '-' }}</p>
            <p>Email: {{ $quotation->contact->email ?? '-' }}</p>
        </div>
    </div>

    {{-- QUOTATION DETAIL --}}
    <h2 class="quotation-title">QUOTATION</h2>
    <table>
        <tr>
            <td>Nomor Quotation</td>
            <td>: {{ $quotation->quotation_number }}</td>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d/m/Y') }}</td>
        </tr>
    </table>

    {{-- ITEMS --}}
    <h3>Rincian Penawaran</h3>
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:55%">Deskripsi</th>
                <th style="width:20%" class="right">Qty</th>
                <th style="width:20%" class="right">Harga Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation->items as $item)
                <tr>
                    <td class="right">{{ $loop->iteration }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL --}}
    <table>
        <tr>
            <td style="width:80%; text-align:right;"><strong>Subtotal</strong></td>
            <td style="width:20%; text-align:right;">{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align:right;"><strong>PPN (11%)</strong></td>
            <td style="text-align:right;">{{ number_format($ppn, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align:right;"><strong>Grand Total</strong></td>
            <td style="text-align:right;"><strong>{{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
        </tr>
    </table>
    {{-- SIGNATURE --}}
    <div class="signature" style="position: relative; width: 100%; margin-top: 50px; height: 150px;">
        <div style="position: absolute; right: 0; text-align: center;">
            Grobogan, {{ date('d/m/Y') }}<br><br>
            <strong>Direktur</strong><br><br>
            <div style="position: relative; display: inline-block; width: 150px; height: auto;">
                {{-- Signature --}}
                @if ($signatureBase64)
                    <img src="{{ $signatureBase64 }}" alt="Signature"
                        style="width: 150px; height: auto; display: block;">
                @endif

                {{-- Stample menimpa signature dengan transparansi --}}
                @if ($stampleBase64)
                    <img src="{{ $stampleBase64 }}" alt="Stample"
                        style="width: 180px; /* lebih lebar agar menutupi */
                            height: auto; 
                            position: absolute; 
                            top: -15px; /* geser ke atas */
                            left: -15px; /* geser ke kiri */
                            z-index: 10;
                            opacity: 0.4;">
                    <!-- Transparansi realistis -->
                @endif
            </div>
            <br>
            <u>Hadiwijaya</u>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Ketitang, Godong, Grobogan, Jawa Tengah | 0852-8074-9218 | info@jowolandborepile.com | www.jowolandborepile.com
    </div>

    {{-- Halaman 2: Term of Payment dan Catatan --}}
    <div class="page-break"></div>

    {{-- HEADER untuk halaman 2 --}}
    <div class="header">
        <div class="logo">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo Perusahaan">
            @endif
        </div>
        <div class="company-info">
            <h1>PT. Jowoland Construction</h1>
            <p>Ketitang, Godong, Grobogan, Jawa Tengah</p>
            <p>Telp: 0852-8074-9218 | Email: info@jowolandborepile.com</p>
        </div>
    </div>

    {{-- QUOTATION INFO untuk halaman 2 --}}
    <div class="quotation-info">
        <div class="from">
            <h3>Dari:</h3>
            <p><strong>PT. Jowoland Construction</strong></p>
            <p>Ketitang, Godong, Grobogan, Jawa Tengah</p>
            <p>Telp: 0852-8074-9218</p>
        </div>
        <div class="to">
            <h3>Kepada:</h3>
            <p><strong>{{ $quotation->contact->name }}</strong></p>
            <p>{{ $quotation->contact->company ?? '-' }}</p>
        </div>
    </div>

    {{-- QUOTATION DETAIL untuk halaman 2 --}}
    <h2 class="quotation-title">SYARAT DAN KETENTUAN</h2>
    <table>
        <tr>
            <td>Nomor Quotation</td>
            <td>: {{ $quotation->quotation_number }}</td>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d/m/Y') }}</td>
        </tr>
    </table>

    {{-- TERM OF PAYMENT --}}
    <div class="terms-container">
        <div class="terms-section">
            <h4>Term Of Payment :</h4>
            <ul>
                <li><strong>Termin 1:</strong> Sebesar 30% dari nilai estimasi, dibayarkan ketika pekerja mulai
                    mengerjakan.</li>
                <li><strong>Termin 2:</strong> Sebesar 40% dari opname lapangan ketika pekerjaan mencapai progress 70%.
                </li>
                <li><strong>Termin 3:</strong> Sebesar 30% dari nilai opname (dihitung 200m¹ per unit mesin, apabila
                    pekerjaan kurang dari minimum charge tersebut) dibayarkan paling lambat 5 hari kalender setelah
                    pekerjaan selesai 100%.</li>
            </ul>
        </div>

        <div class="terms-section">
            <h4>Catatan :</h4>
            <ul>
                <li>Penawaran harga belum termasuk PPH</li>
                <li>Penawaran Unit Fixed Price</li>
                <li>Pengeboran dihitung dari muka tanah asal</li>
                <li>Upah mengeboran yang ditawarkan adalah pengeboran dengan struktur tanah lempung/clay/dibawah NSPT 50
                    apabila ditemukan batu (tanah keras) pekerjaan akan dihentikan</li>
                <li>Apabila ada penambahan unit untuk penunjang percepatan akan dikenakan biaya mob dan demobilisasi
                    kembali</li>
                <li>Air Kerja by Pemberi Kerja</li>
                <li>Listrik Kerja by Pemberi Kerja</li>
                <li>Buang limbah bor keluar lokasi proyek by Pemberi Kerja</li>
                <li>Material (Besi dan Beton) By Pemberi Kerja</li>
                <li>Apabila ada kerja tambah akan dibuatkan addendum yang akan di hitung sesuai volume lapangan</li>
                <li>Apabila pekerjaan selesai 100% pelunasan dalam mundur 5 hari kalender</li>
                <li>Keamanan internal / external by pemberi Kerja</li>
                <li>Penundaan pekerjaan akibat dari keterlambatan pengadaan material, perubahan spek, gambar, dan
                    penundaan lainnya akan dikenakan denda idle time Rp 500.000,- per hari</li>
            </ul>
        </div>

        <div class="terms-section">
            <p>Demikian penawaran harga yang kami ajukan, besar harapan kami dapat terjalin hubungan baik, atas
                perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
        </div>
    </div>

    {{-- SIGNATURE --}}
    <div class="signature" style="position: relative; width: 100%; margin-top: 50px; height: 150px;">
        <div style="position: absolute; right: 0; text-align: center;">
            Grobogan, {{ date('d/m/Y') }}<br><br>
            <strong>Direktur</strong><br><br>
            <div style="position: relative; display: inline-block; width: 150px; height: auto;">
                {{-- Signature --}}
                @if ($signatureBase64)
                    <img src="{{ $signatureBase64 }}" alt="Signature"
                        style="width: 150px; height: auto; display: block;">
                @endif

                {{-- Stample menimpa signature dengan transparansi --}}
                @if ($stampleBase64)
                    <img src="{{ $stampleBase64 }}" alt="Stample"
                        style="width: 180px; /* lebih lebar agar menutupi */
                            height: auto; 
                            position: absolute; 
                            top: -15px; /* geser ke atas */
                            left: -15px; /* geser ke kiri */
                            z-index: 10;
                            opacity: 0.4;">
                    <!-- Transparansi realistis -->
                @endif
            </div>
            <br>
            <u>Hadiwijaya</u>
        </div>
    </div>


    {{-- FOOTER untuk halaman 2 --}}
    <div class="footer">
        Ketitang, Godong, Grobogan, Jawa Tengah | 0852-8074-9218 | info@jowolandborepile.com | www.jowolandborepile.com
    </div>
</body>

</html>
