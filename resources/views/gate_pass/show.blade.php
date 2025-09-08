<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Pass</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .duplicate-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #17a2b8;
            color: white;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 1000;
        }
        .card-body { position: relative; }
        @media print { .no-print { display: none; } }
        
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg position-relative p-3">

       
        <div class="card-header  text-black text-center">
            <h3>Gate Pass</h3>
        </div>

        <div class="card-body position-relative">

        <p class="d-flex align-items-left">    @if($gatePass->status === 'DUPLICATE PASS')
                <div class="duplicate-badge">DUPLICATE PASS</div>
            @endif </p>
        
            <p><strong>Pass No:</strong> {{ $gatePass->gate_pass_no }}</p>
            <p><strong>Invoice No:</strong> {{ $gatePass->invoice->invoice_no ?? $gatePass->invoice_id }}</p>
            <p><strong>User:</strong> {{ $gatePass->user->name }}</p>
            <p><strong>Total Items:</strong> {{ rtrim(rtrim(number_format($totalItems, 2), '0'), '.') }}</p>
           <p><strong>Customer:</strong> {{ $gatePass->invoice->buyer->name ?? 'N/A' }}</p>
         <p><strong>Contact No:</strong>{{ $gatePass->invoice->buyer->contact_no ?? 'N/A' }}</p>
        </div>
        
            <h5 class="mt-4">Invoice Items</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach($gatePass->invoice->items as $item)
                        <tr>
                            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->qty, 2), '0'), '.') }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->price, 2), '0'), '.') }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->total, 2), '0'), '.') }}</td>
                         
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 no-print text-center">
                <button onclick="window.print()" class="btn btn-success">Print Gate Pass</button>
               
                    <button class="btn btn-secondary" onclick="window.location.href='/'">
                        Back
                    </button>
           
            </div>
        </div>
    </div>
</div>
</body>
</html>
