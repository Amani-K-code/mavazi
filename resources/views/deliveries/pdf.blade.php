<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Record #{{ $delivery->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0f172a; padding-bottom: 10px; }
        .school-name { font-size: 24px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .doc-type { font-size: 14px; color: #64748b; letter-spacing: 2px; }
        
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { vertical-align: top; }
        .label { font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; }
        .value { font-size: 13px; font-weight: bold; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background: #f8fafc; font-size: 11px; text-transform: uppercase; padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        
        .total-box { margin-top: 30px; text-align: right; }
        .total-amount { font-size: 18px; font-weight: bold; color: #0f172a; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .pending { background: #fef3c7; color: #92400e; }
        .confirmed { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">Logos Christian School</div>
        <div class="doc-type">Inventory Delivery Record</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <div class="label">Reference ID</div>
                <div class="value">#DEL-{{ str_pad($delivery->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="label" style="margin-top:10px">Registered By</div>
                <div class="value">{{ $delivery->user->name }}</div>
            </td>
            <td width="50%" style="text-align: right">
                <div class="label">Delivery Date</div>
                <div class="value">{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d M, Y') }}</div>
                <div class="label" style="margin-top:10px">Payment Due Date</div>
                <div class="value">{{ \Carbon\Carbon::parse($delivery->payment_due_date)->format('d M, Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Item Description</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delivery->items as $item)
            <tr>
                <td style="font-weight: bold">{{ $item->item_name }}</td>
                <td>{{ $item->size }}</td>
                <td>{{ $item->quantity }} pcs</td>
                <td style="color: #64748b">{{ $item->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="label">Total Invoice Amount</div>
        <div class="total-amount">Ksh {{ number_format($delivery->total_invoice_amount, 2) }}</div>
        <div style="margin-top: 10px">
            <span class="status-badge {{ strtolower($delivery->status) }}">
                Status: {{ $delivery->status }}
            </span>
        </div>
    </div>

    <div style="margin-top: 50px; font-size: 10px; color: #94a3b8; text-align: center;">
        This is a computer-generated document. Approval required by School Admin for stock validation.
    </div>
</body>
</html>