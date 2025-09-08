<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .duplicate {
            position: absolute;
            top: 40%;
            left: 20%;
            font-size: 80px;
            color: rgba(255,0,0,0.3);
            transform: rotate(-30deg);
        }
    </style>
</head>
<body>
    @if($gatePass->is_duplicate)
        <div class="duplicate">DUPLICATE</div>
    @endif

    <h2>Gate Pass: {{ $gatePass->pass_no }}</h2>
    <p>Invoice ID: {{ $gatePass->invoice_id }}</p>
    <p>Date: {{ $gatePass->created_at->format('d-m-Y') }}</p>
</body>
</html>
