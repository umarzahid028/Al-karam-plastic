<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">

    <h3 class="mb-4">Sales Report</h3>

    <!-- Total Sales Card -->
    <div class="mb-4">
        <div class="card shadow-sm p-3">
            <h5>Total Sales</h5>
            <div class="fs-3 fw-bold text-success">
                {{ rtrim(rtrim(number_format($totalSales, 2), '0'), '.') }}
            </div>
        </div>
    </div>

    <!-- Sales Detail Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Sales Invoices Detail</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>City</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $row)
                        <tr>
                            <td>{{ $row->invoice_no }}</td>
                            <td>{{ $row->invoice_date }}</td>
                            <td>{{ $row->customer_name }}</td>
                            <td>{{ $row->city }}</td>
                            <td>{{ $row->product }}</td>
                            <td>{{ $row->qty }}</td>
                            <td>{{ number_format($row->price, 2) }}</td>
                            <td>{{ number_format($row->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>