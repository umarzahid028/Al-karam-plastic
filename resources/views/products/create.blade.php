<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Add Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f5f7fa; font-family: Arial, sans-serif; }
.box { background:white; border-radius:12px; padding:25px; max-width:700px; margin:50px auto; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
.btn-purple { background:#6366f1; color:white;}
.btn-purple:hover { background:#4f46e5; color:white;}
</style>
</head>
<body>
<div class="box">
    <h4 class="mb-4">Add New Product</h4>

    <!-- Product Form -->
    <form id="productForm">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Product Code</label>
                <input type="text" id="product_code" class="form-control" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Product Name</label>
                <input type="text" id="product_name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Group</label>
                <input type="text" id="product_group" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Unit</label>
                <input type="text" id="unit" class="form-control" placeholder="kg">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sale Price</label>
                <input type="number" step="0.01" id="sale_price" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cost Price</label>
                <input type="number" step="0.01" id="cost_price" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Size</label>
                <input type="text" id="size" class="form-control" placeholder="10mm">
            </div>
            <div class="col-md-4">
                <label class="form-label">Packing</label>
                <input type="text" id="packing_sqr" class="form-control" placeholder="Bundle">
            </div>
            <div class="col-md-4">
                <label class="form-label">Pieces / Bundle</label>
                <input type="number" id="pieces_per_bundle" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Weight</label>
                <input type="number" step="0.01" id="weight" class="form-control">
            </div>
        </div>

        <!-- Opening Stock -->
        <h6 class="mt-4">Opening Stock (Optional)</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Qty</label>
                <input type="number" id="opening_qty" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" id="opening_price" class="form-control" value="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Remarks</label>
                <input type="text" id="opening_remarks" class="form-control" placeholder="Opening Stock">
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-purple">Save Product</button>
            {{-- <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button> --}}
            <button type="button" class="btn btn-secondary" onclick="window.location.href='/api/products'">
                Back
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById("productForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        product_code: document.getElementById("product_code").value,
        product_name: document.getElementById("product_name").value,
        product_group: document.getElementById("product_group").value,
        unit: document.getElementById("unit").value,
        sale_price: parseFloat(document.getElementById("sale_price").value)||0,
        cost_price: parseFloat(document.getElementById("cost_price").value)||0,
        size: document.getElementById("size").value,
        packing_sqr: document.getElementById("packing_sqr").value,
        pieces_per_bundle: parseInt(document.getElementById("pieces_per_bundle").value)||0,
        weight: parseFloat(document.getElementById("weight").value)||0,
        opening_qty: parseInt(document.getElementById("opening_qty").value)||0,
        opening_price: parseFloat(document.getElementById("opening_price").value)||0,
        opening_remarks: document.getElementById("opening_remarks").value
    };

    fetch("http://127.0.0.1:8000/api/products", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify(data)
    })
    .then(res=>res.json())
    .then(resp=>{
        if(resp.success){
            alert("✅ Product added successfully!");
            document.getElementById("productForm").reset();
        }else{
            alert("❌ Error: "+resp.message);
        }
    })
    .catch(err=>alert("Request failed: "+err));
});
</script>
</body>
</html>
