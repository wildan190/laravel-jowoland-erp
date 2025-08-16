<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - PT. Jowoland Construction</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #000000;
            margin: 0;
            padding: 0;
            line-height: 1.5;
            background-color: #ffffff;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            color: #000000;
        }

        .header .company-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #333333;
        }

        /* INVOICE INFO */
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-info .from,
        .invoice-info .to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-info h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            border-bottom: 1px solid #FFD700;
        }

        .invoice-info p {
            margin: 2px 0;
            font-size: 11px;
            color: #333333;
        }

        /* INVOICE TITLE */
        .invoice-title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #000000;
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background-color: #FFD700;
            color: #000000;
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
            color: #000000;
        }

        /* FOOTER */
        .footer {
            border-top: 2px solid #FFD700;
            margin-top: 30px;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
            color: #333333;
            background-color: #fff;
        }

        /* Responsive Design */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .header,
            table {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
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

    <!-- INVOICE INFO -->
    <div class="invoice-info">
        <div class="from">
            <h3>Invoice From:</h3>
            <p><strong>PT. Jowoland Construction</strong></p>
            <p>Ketitang, Godong, Grobogan, Jawa Tengah</p>
            <p>Telp: 0852-8074-9218</p>
            <p>Email: info@jowolandborepile.com</p>
        </div>
        <div class="to">
            <h3>Invoice To:</h3>
            <p><strong>{{ $invoice->project->contact->company }}</strong></p>
            <p>{{ $invoice->project->contact->name }}</p>
            <p>{{ $invoice->project->contact->address }}</p>
            <p>Telp: {{ $invoice->project->contact->phone }}</p>
            <p>Email: {{ $invoice->project->contact->email }}</p>
        </div>
    </div>

    <!-- INVOICE DETAIL -->
    <h2 class="invoice-title">INVOICE</h2>
    <table>
        <tr>
            <td>Nomor Invoice</td>
            <td>: {{ $invoice->invoice_number }}</td>
            <td>Tanggal Terbit</td>
            <td>: {{ date('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Project</td>
            <td>: {{ $invoice->project->name }}</td>
            <td>Jatuh Tempo</td>
            <td>: {{ $invoice->due_date }}</td>
        </tr>
    </table>

    <h3>Rincian Tagihan</h3>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="right">Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->project->name }}</td>
                <td class="right">{{ number_format($invoice->project_amount, 0, ',', '.') }}</td>
            </tr>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Subtotal</strong></td>
                <td class="right">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>PPN (11%)</strong></td>
                <td class="right">{{ number_format($ppn, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Grand Total</strong></td>
                <td class="right"><strong>{{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- SIGNATURE -->
    <div class="signature">
        Jakarta, {{ date('d/m/Y') }}<br>
        <strong>Direktur</strong><br><br><br>
        <u>Andi Pratama</u>
    </div>

    <!-- FOOTER -->
    <div class="footer"
        style="border-top:2px solid #000; margin-top:30px; padding-top:10px; font-size:10px; text-align:center; color:#555;">
        <span style="margin-right:15px;">Ketitang, Godong, Grobogan, Jawa Tengah</span>
        <span style="margin-right:15px;">0852-8074-9218</span>
        <span style="margin-right:15px;">info@jowolandborepile.com</span>
        <span>www.jowolandborepile.com</span>
    </div>

</body>

</html>
