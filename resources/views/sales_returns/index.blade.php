<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
 
<div class="container py-4">
    <h3>Sales Return</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('sales_returns.search') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="invoice_no" class="form-control" placeholder="Enter Invoice Number" required>
            <button class="btn btn-primary">Search</button>
        </div>
    </form>
</div>


</body>
</html>