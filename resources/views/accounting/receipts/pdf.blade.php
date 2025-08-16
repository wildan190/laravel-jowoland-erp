<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 2px solid #000;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .table-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-details th, .table-details td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        .footer .signature {
            text-align: center;
        }
        .footer .signature p {
            margin: 5px 0;
        }
        .footer .signature .line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 10px auto;
        }
        .note {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="logo-placeholder.png" alt="Company Logo">
            <h2>KWITANSI PEMBAYARAN</h2>
            <p>No: {{ $receipt->receipt_number }}</p>
            <p>Tanggal: {{ $receipt->date->format('d-m-Y') }}</p>
        </div>

        <div class="details">
            <table class="table-details">
                <tr>
                    <th>Invoice</th>
                    <td>{{ $receipt->invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <th>Proyek</th>
                    <td>{{ $receipt->invoice->project->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>Rp {{ number_format($receipt->amount, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <p class="note">Terima kasih atas pembayaran Anda.</p>

        <div class="footer">
            <div class="signature">
                <p>Penerima,</p>
                <div class="line"></div>
                <p>(Nama Penerima)</p>
            </div>
            <div class="signature">
                <p>Pembayar,</p>
                <div class="line"></div>
                <p>(Nama Pembayar)</p>
            </div>
        </div>
    </div>
</body>
</html>