<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mavazi Monthly Performance - {{ $monthName }}</title>
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
        .stat-card { background: white; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; text-align: center; }
        .stat-label { font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
        .stat-value { font-size: 20px; font-weight: 900; color: #003366; }

        /* Performance Chart (CSS based) */
        .section-header { font-size: 14px; font-weight: 900; color: #003366; border-left: 4px solid #fbbf24; padding-left: 10px; margin-bottom: 20px; text-transform: uppercase; }
        
        .chart-row { margin-bottom: 15px; }
        .chart-label { font-size: 11px; font-weight: bold; margin-bottom: 5px; }
        .chart-container { background: #e2e8f0; height: 12px; border-radius: 6px; overflow: hidden; }
        .chart-bar { background: #003366; height: 100%; border-radius: 6px; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; border-radius: 15px; overflow: hidden; }
        th { background-color: #f1f5f9; padding: 15px; text-align: left; font-size: 10px; text-transform: uppercase; color: #64748b; font-weight: 900; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        .row-id { font-weight: bold; color: #003366; }

        .footer { position: fixed; bottom: 0; width: 100%; background: #003366; color: white; text-align: center; font-size: 9px; padding: 10px 0; }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo">
        <div class="header-text">
            <div style="font-size: 22px; font-weight: 900; text-transform: uppercase; margin-bottom: 2px;">Logos Christian School</div>
            <div style="font-style: italic; font-size: 11px; color: #fbbf24; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Educating for Life and Eternity</div>
            
            <h1 class="report-title">Monthly Sales Report</h1>
            <div class="report-subtitle">PERFORMANCE ANALYTICS • {{ strtoupper($monthName) }} {{ $year }}</div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="content">
        <table class="stats-grid">
            <tr>
                <td style="padding: 0 10px 0 0; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-value">KSh {{ number_format($totalRevenue) }}</div>
                    </div>
                </td>
                <td style="padding: 0 10px; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-value">{{ $sales->count() }}</div>
                    </div>
                </td>
                <td style="padding: 0 0 0 10px; width: 33%;">
                    <div class="stat-card">
                        <div class="stat-label">Average Sale</div>
                        <div class="stat-value">KSh {{ number_format($sales->avg('total_amount')) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-header">Category Performance Distribution</div>
        <div style="background: white; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 40px;">
            @foreach($categoryStats as $stat)
                @php $percentage = ($stat->total / $totalRevenue) * 100; @endphp
                <div class="chart-row">
                    <div class="chart-label">
                        <span style="float: left;">{{ $stat->category }}</span>
                        <span style="float: right; color: #64748b;">KSh {{ number_format($stat->total) }} ({{ round($percentage) }}%)</span>
                        <div style="clear: both;"></div>
                    </div>
                    <div class="chart-container">
                        <div class="chart-bar" style="width: {{ $percentage }}%; background: {{ $loop->first ? '#fbbf24' : '#003366' }};"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="section-header">Recent Transaction Detailed Log</div>
        <table>
            <thead>
                <tr>
                    <th>Ref No.</th>
                    <th>Customer</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td class="row-id">#{{ $sale->id }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $sale->customer_name }}</div>
                        <div style="font-size: 9px; color: #64748b;">{{ $sale->created_at->format('d M, Y') }}</div>
                    </td>
                    <td>{{ $sale->payment_method }}</td>
                    <td>
                        <span style="color: {{ $sale->status == 'CONFIRMED' ? '#166534' : '#854d0e' }}; font-weight: bold; font-size: 10px;">
                            {{ $sale->status }}
                        </span>
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
        MAVAZI INVENTORY SYSTEM • LOGOS CHRISTIAN SCHOOL • Generated on {{ date('d-m-Y H:i') }}
    </div>
</body>
</html>