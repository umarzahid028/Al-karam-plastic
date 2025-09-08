<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        .card-header {
         
            color: black;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 15px 20px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        .btn-secondary {
            border-radius: 8px;
            padding: 10px 20px;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: none !important;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"> Edit Product</h4>
        </div>
        <div class="card-body p-4">
            <form action="/products/1" method="POST">
                <!-- Laravel ke liye CSRF aur PUT method field add karni hoti hai -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Product Code</label>
                        <input type="text" name="product_code" class="form-control" value="P001" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" value="Sample Product" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Group</label>
                        <input type="text" name="product_group" class="form-control" value="Tiles">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="Box">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sale Price</label>
                        <input type="number" step="0.01" name="sale_price" class="form-control" value="250" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="200" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Opening Qty</label>
                        <input type="number" name="opening_qty" class="form-control" value="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Opening Price</label>
                        <input type="number" step="0.01" name="opening_price" class="form-control" value="180">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Opening Remarks</label>
                        <input type="text" name="opening_remarks" class="form-control" value="Initial stock">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="/" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
