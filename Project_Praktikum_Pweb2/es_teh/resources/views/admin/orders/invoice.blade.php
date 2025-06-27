<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f2f5;
            /* Warna background abu-abu muda */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.07);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .invoice-header .logo-container img {
            max-width: 120px;
        }

        .invoice-header .invoice-details {
            text-align: right;
        }

        .invoice-header .invoice-details h2 {
            margin: 0;
            font-size: 2rem;
            color: #333;
        }

        .invoice-header .invoice-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }

        .customer-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .customer-details div {
            font-size: 0.9rem;
        }

        .items-table table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        .items-table thead th {
            background-color: #f8f9fa;
            color: #343a40;
            padding: 0.75rem;
            border-bottom: 2px solid #dee2e6;
        }

        .items-table tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid #f0f2f5;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .invoice-summary {
            margin-top: 1.5rem;
            text-align: right;
        }

        .invoice-summary table {
            width: 40%;
            margin-left: auto;
        }

        .invoice-summary table td {
            padding: 0.5rem;
        }

        .invoice-summary .total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #0d6efd;
        }

        .invoice-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f2f5;
            font-size: 0.8rem;
            color: #999;
        }

        .no-print {
            text-align: center;
            margin-top: 2rem;
        }

        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                border: 0;
                box-shadow: none;
                border-radius: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <header class="invoice-header">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Toko">
            </div>
            <div class="invoice-details">
                <h2 class="fw-bold">INVOICE</h2>
                <p><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y') }}</p>
            </div>
        </header>

        <section class="customer-details">
            <div>
                <h5 class="fw-semibold mb-2">Ditagihkan Kepada:</h5>
                <p class="mb-0"><strong>{{ $order->customer_name }}</strong></p>
                <p class="mb-0">{{ nl2br(e($order->shipping_address)) }}</p>
                <p class="mb-0">{{ $order->customer_phone }}</p>
                <p class="mb-0">{{ $order->customer_email }}</p>
            </div>
            <div class="text-end">
                <h5 class="fw-semibold mb-2">Info Pembayaran:</h5>
                <p class="mb-0">Metode: {{ $order->payment_method }}</p>
                <p class="mb-0">Status: <span class="fw-bold">{{ ucfirst($order->payment_status) }}</span></p>
            </div>
        </section>

        <section class="items-table">
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi Produk</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product->name ?? 'Produk Telah Dihapus' }}
                                @if (isset($item->product->size))
                                    <br><small class="text-muted">Ukuran: {{ $item->product->size }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="invoice-summary">
            <table>
                {{-- Anda bisa tambahkan biaya pengiriman, diskon, dll. di sini jika ada --}}
                {{-- <tr>
                    <td>Subtotal</td>
                    <td class="text-end">Rp ...</td>
                </tr> --}}
                <tr>
                    <td class="border-top pt-3"><strong>Total</strong></td>
                    <td class="border-top pt-3 text-end total"><strong>Rp
                            {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </section>

        <footer class="invoice-footer">
            Terima kasih telah berbelanja di Es Teh Poci Aji Manis!
        </footer>
    </div>

    <div class="no-print mb-5">
        <button onclick="window.print()" class="btn btn-primary shadow-sm"><i class="bi bi-printer-fill"></i> Cetak
            Struk / Simpan sebagai PDF</button>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary shadow-sm">Kembali ke Detail
            Pesanan</a>
    </div>
</body>

</html>
