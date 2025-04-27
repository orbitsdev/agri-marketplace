<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report' }} - Agri Marketplace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 15px;
        }
        .print-container {
            background-color: white;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #2c5282;
            padding-bottom: 10px;
        }
        .report-header h1 {
            color: #2c5282;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .report-header p {
            color: #666;
            margin: 3px 0;
            font-size: 14px;
        }
        .report-content {
            margin-bottom: 20px;
        }
        .report-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #2c5282;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .subtotal-row th, .subtotal-row td {
            background-color: #e2e8f0;
            font-weight: bold;
            color: #1a202c;
        }
        .summary p {
            margin: 5px 0;
            font-size: 14px;
        }
        .action-buttons {
            text-align: center;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            margin: 0 5px;
        }
        .btn-primary {
            background-color: #2c5282;
            color: white;
        }
        .btn-secondary {
            background-color: #666;
            color: white;
        }
        @media print {
            .no-print {
                display: none;
            }
            .print-container {
                box-shadow: none;
                padding: 0;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="action-buttons no-print">
            <a href="{{ route('filament.farmer.pages.reports') }}" class="btn btn-secondary">Back to Reports</a>
            <button onclick="printReport()" class="btn btn-primary">Print Report</button>
        </div>
        
        <div class="print-container">
            <div class="report-header">
                <h1>{{ $title ?? 'Report' }}</h1>
                <p>{{ $subtitle ?? 'Agri Marketplace' }}</p>
                <p>Generated: {{ now()->format('F j, Y') }}</p>
            </div>
            
            <div class="report-content">
                @yield('content')
            </div>
            
            <div class="report-footer">
                <p>© {{ date('Y') }} Agri Marketplace</p>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>
