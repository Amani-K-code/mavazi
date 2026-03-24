<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mavazi Audit Report - {{ $summary['period'] }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; line-height: 1.4; margin: 0; padding: 0; background-color: #f8fafc; }
        
        /* Premium Header */
        .header { background-color: #003366; color: white; padding: 30px 50px; border-bottom: 8px solid #fbbf24; }
        .logo { height: 60px; float: left; }
        .header-text { float: right; text-align: right; }
        .report-title { font-size: 24px; font-weight: 900; text-transform: uppercase; margin: 0; letter-spacing: 2px; }
        .report-subtitle { color: #fbbf24; font-size: 12px; font-weight: bold; margin-top: 5px; }
        .clearfix { clear: both; }

        .content { padding: 40px 50px; }

        /* Stats Dashboard Grid */
        .stats-grid { width: 100%; margin-bottom: 40px; }
        .stat-card { background: white; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; text-align: center; border-bottom: 4px solid #fbbf24; }
        .stat-label { font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
        .stat-value { font-size: 20px; font-weight: 900; color: #003366; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; border-radius: 15px; overflow: hidden; }
        th { background-color: #f1f5f9; padding: 15px; text-align: left; font-size: 10px; text-transform: uppercase; color: #64748b; font-weight: 900; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 11px; vertical-align: top; }
        .row-id { font-weight: bold; color: #003366; }

        .footer { position: fixed; bottom: 0; width: 100%; background: #003366; color: white; text-align: center; font-size: 9px; padding: 10px 0; }
        .item-list { font-size: 9px; color: #475569; margin-top: 4px; line-height: 1.2; }
    </style>
</head>

<body>
    <div class="header">
        {{-- Verify this path exists in your public folder --}}
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div class="header-text">
            <div style="font-size: 22px; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">Logos Christian School</div>
            <div style="font-style: italic; font-size: 11px; color: #fbbf24; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Educating for Life and Eternity</div>
            
            <h1 class="report-title">Audit Sales Report</h1>
            <div class="report-subtitle">DATA EXTRACT • {{ strtoupper($summary['period']) }}</div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="content">
        {{-- 1. Summary Header Cards --}}
        <table class="stats-grid">
            <tr>
                <td style="padding: 0 10px 0 0; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Filtered Revenue</div>
                        <div class="stat-value">KSh {{ number_format($totalRevenue) }}</div>
                    </div>
                </td>
                <td style="padding: 0 10px; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Transactions</div>
                        <div class="stat-value">{{ $summary['total_orders'] }}</div>
                    </div>
                </td>
                <td style="padding: 0 0 0 10px; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Avg. Transaction</div>
                        <div class="stat-value">KSh {{ number_format($summary['avg_order']) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- 2. Detailed Audit Log --}}
        <div style="font-size: 14px; font-weight: 900; color: #003366; border-left: 4px solid #fbbf24; padding-left: 10px; margin-bottom: 20px; text-transform: uppercase;">Detailed Transaction Log</div>
        
        <table>
            <thead>
                <tr>
                    <th>Ref / Date</th>
                    <th>Customer Name</th>
                    <th>Items Purchased</th>
                    <th>Staff Member</th>
                    <th style="text-align: right;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td class="row-id">
                        #{{ $sale->id }}<br>
                        <span style="font-size: 9px; font-weight: normal; color: #64748b;">{{ $sale->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <div style="font-weight: bold; text-transform: uppercase;">{{ $sale->customer_name }}</div>
                        <div style="font-size: 9px; color: #64748b;">Status: {{ $sale->status }}</div>
                    </td>
                    <td>
                        <div class="item-list">
                            @foreach($sale->saleItems as $item)
                                • {{ $item->inventory->item_name }} (x{{ $item->quantity }})<br>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: bold;">{{ $sale->user->name ?? 'System' }}</div>
                        <div style="font-size: 9px; color: #64748b;">{{ $sale->user->role ?? '' }}</div>
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #003366;">
                        KSh {{ number_format($sale->total_amount) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        MAVAZI AUDIT SYSTEM • GENERATED ON {{ $generated_at }} • CONFIDENTIAL DOCUMENT
    </div>
</body>
</html>