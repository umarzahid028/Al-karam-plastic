<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

    <div class="container">
        <h3>Search Purchase Invoice</h3>
        <form action="{{ route('purchase_returns.search.get') }}" method="GET">
            <input type="text" name="invoice_no" placeholder="Enter Invoice No">
            <button type="submit">Search</button>
        </form>
        
        
    </div>
   
      
</body>
</html>