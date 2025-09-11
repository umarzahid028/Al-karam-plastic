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
    <h3>Return Items for Invoice: {{ $purchase->purchase_code }}</h3>

    <form method="POST" action="{{ route('purchase_returns.store', $purchase) }}">
        @csrf
        <table class="table">
            @foreach($purchase->items as $item)
                <tr>
                    <td>{{ $item->rawMaterial->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        <input type="number"
                               name="items[{{ $item->id }}][return_qty]"
                               min="0"
                               max="{{ $item->quantity }}"
                               class="form-control">
                    </td>
                </tr>
            @endforeach
        </table>
        <textarea name="remarks" class="form-control"></textarea>
        <button type="submit" class="btn btn-primary">Return Items</button>
    </form>
    
</div>


</body>
</html>