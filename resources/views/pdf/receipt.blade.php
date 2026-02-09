<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $sale->receipt_no }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        .header { background-color: #003366; color: white; padding: 40px; text-align: center; }
        .logo { height: 70px; margin-bottom: 10px; }
        .motto { font-style: italic; font-size: 12px; color: #fbbf24; text-transform: uppercase; letter-spacing: 1px; }
        
        .content { padding: 30px; }
        .details-grid { width: 100%; margin-bottom: 30px; }
        .details-grid td { vertical-align: top; width: 50%; }
        
        .label { font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-size: 14px; font-weight: bold; color: #003366; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; border-bottom: 2px solid #003366; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #64748b; }
        td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        
        .total-section { margin-top: 30px; text-align: right; }
        .total-box { display: inline-block; background: #f8fafc; padding: 15px 30px; border-radius: 10px; border-left: 5px solid #fbbf24; }
        
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 15px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-confirmed { background: #dcfce7; color: #166534; }
        .status-booked { background: #fef9c3; color: #854d0e; }

        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; pt: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div style="font-size: 22px; font-weight: 900; text-transform: uppercase;">Logos Christian School</div>
        <div class="motto">Educating for Life and Eternity</div>
    </div>

    <div class="content">
        <table class="details-grid">
            <tr>
                <td>
                    <div class="label">Customer Details</div>
                    <div class="value">Parent: {{ $sale->customer_name }}</div>
                    <div class="value">Student: {{ $sale->child_name }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="label">Receipt Information</div>
                    <div class="value">{{ $sale->receipt_no }}</div>
                    <div class="value">{{ $sale->created_at->format('d M, Y - h:i A') }}</div>
                    <div class="status-badge {{ $sale->status === 'CONFIRMED' ? 'status-confirmed' : 'status-booked' }}">
                        {{ $sale->status }}
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #003366;">{{ $item->inventory->item_name }}</div>
                        <div style="font-size: 10px; color: #64748b;">Size: {{ $item->inventory->size_label }}</div>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">KES {{ number_format($item->unit_price) }}</td>
                    <td style="text-align: right; font-weight: bold;">KES {{ number_format($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-box">
                <div class="label">Total Amount Paid</div>
                <div style="font-size: 24px; font-weight: 900; color: #003366;">KES {{ number_format($sale->total_amount) }}</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 5px;">Payment Method: {{ $sale->payment_method }} | Ref: {{ $sale->reference_id }}</div>
                @if($sale->status === 'BOOKED' && $sale->expiry_date)
                    <div style="font-size: 10px; color: #ef4444; font-weight: bold; margin-top: 5px;">BOOKING EXPIRES ON: {{ \Carbon\Carbon::parse($sale->expiry_date)->format('d M, Y') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        Thank you for choosing Logos Christian School Mavazi Shop.<br>
        This is a computer-generated receipt and does not require a signature.
    </div>
</body>
</html>