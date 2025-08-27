<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KWITANSI {{ $receipt->receipt_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&family=Roboto:wght@300;400;500;700&display=swap');

        .receipt-border {
            border: 2px solid #1a365d;
            border-radius: 5px;
            padding: 20px;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e0e0e0;
        }

        .company-info {
            flex: 2;
        }

        .receipt-title {
            font-family: 'Roboto Mono', monospace;
            font-size: 20px;
            font-weight: 700;
            color: #1a365d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .receipt-number {
            font-family: 'Roboto Mono', monospace;
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
            margin-top: 5px;
        }

        .logo-container {
            flex: 1;
            text-align: right;
        }

        .logo-container img {
            max-height: 55px;
        }

        .receipt-details {
            margin-bottom: 15px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 8px;
            margin-bottom: 12px;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
        }

        .detail-value {
            font-weight: 500;
        }

        .amount-section {
            background: #f7fafc;
            padding: 12px;
            border-radius: 5px;
            border-left: 3px solid #1a365d;
            margin: 15px 0;
        }

        .amount-label {
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
        }

        .amount-value {
            font-family: 'Roboto Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #1a365d;
        }

        .table-section {
            margin: 20px 0;
        }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
            color: #718096;
            font-size: 11px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .receipt-date {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 11px;
            color: #718096;
            font-weight: 500;
        }

        .watermark {
            position: absolute;
            opacity: 0.03;
            font-size: 100px;
            font-weight: 900;
            color: #1a365d;
            transform: rotate(-45deg);
            top: 40%;
            left: 20%;
            z-index: -1;
            pointer-events: none;
            font-family: 'Roboto Mono', monospace;
        }

        @media print {
            html, body {
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
                padding: 0;
                background: none;
            }

            .receipt-container {
                box-shadow: none;
                border: 1px solid #ccc;
                width: auto;
                max-width: 600px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">KWITANSI</div>
        <div class="receipt-border">
            <div class="receipt-header">
                <div class="company-info">
                    <h1 class="receipt-title">Kwitansi Pembayaran</h1>
                    <div class="receipt-number">No: {{ $receipt->receipt_number }}</div>
                </div>
                <div class="logo-container">
                    <img src="data:image/png;base64,{{ $logo }}" alt="Company Logo">
                </div>
            </div>

            <div class="receipt-date">
                Tanggal: {{ $receipt->date->format('d-m-Y') }}
            </div>

            <div class="receipt-details">
                <div class="detail-grid">
                    <div class="detail-label">Invoice</div>
                    <div class="detail-value">{{ $receipt->invoice->invoice_number }}</div>

                    <div class="detail-label">Proyek</div>
                    <div class="detail-value">{{ $receipt->invoice->project->name ?? '-' }}</div>

                    <div class="detail-label">Keterangan</div>
                    <div class="detail-value">Pembayaran invoice {{ $receipt->invoice->invoice_number }}</div>
                </div>
            </div>

            <div class="amount-section">
                <div class="amount-label">Jumlah yang Dibayarkan</div>
                <div class="amount-value">Rp {{ number_format($receipt->amount, 2, ',', '.') }}</div>
            </div>

            <div class="table-section">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding: 10px; text-align: center; vertical-align: top;">
                            <div class="signature-label">Penerima</div>
                            <div style="min-height: 60px; position: relative;">
                                <img style="max-height: 50px;" src="data:image/png;base64,{{ $signature }}" alt="Signature">
                            </div>
                            <div style="width: 80%; border-top: 1px solid #4a5568; margin: 8px auto;"></div>
                            <div style="font-weight: 500; font-size: 11px;">Achmad Fikri Ibnu Hadi</div>
                            <div style="font-size: 10px; color: #718096;">Finance Manager</div>
                        </td>
                        <td style="width: 50%; padding: 10px; text-align: center; vertical-align: top;">
                            <div class="signature-label">Pembayar</div>
                            <div style="min-height: 60px;"></div>
                            <div style="width: 80%; border-top: 1px solid #4a5568; margin: 8px auto;"></div>
                            <div style="font-weight: 500; font-size: 11px;">(________________)</div>
                            <div style="font-size: 10px; color: #718096;">(________________)</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer-note">
                Terima kasih atas pembayaran Anda. Dokumen ini merupakan bukti pembayaran yang sah.
            </div>
        </div>
    </div>
</body>
</html>
