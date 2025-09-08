<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Raw Material</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f0f2f5;
    padding: 20px;
}
.container {
    max-width: 700px;
    margin: 50px auto;
}
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    padding: 30px;
}
.card h3 {
    margin-bottom: 25px;
    color: #333;
}
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
}
.btn-primary {
    background: #0d6efd;
    border: none;
    padding: 10px 25px;
    border-radius: 6px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
.btn-primary:hover {
    background: #0b5ed7;
}
label {
    font-weight: 500;
    color: #555;
}
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <h3>Create New Raw Material</h3>
        <form id="rawMaterialForm">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Material Code</label>
                    <input type="text" class="form-control" id="material_code" placeholder="Enter code" required>
                </div>
                <div class="col-md-6">
                    <label>Material Name</label>
                    <input type="text" class="form-control" id="material_name" placeholder="Enter name" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Unit</label>
                    <input type="text" class="form-control" id="unit" placeholder="e.g., litre" required>
                </div>
                <div class="col-md-4">
                    <label>Packing</label>
                    <input type="text" class="form-control" id="packing" placeholder="e.g., bottle/box">
                </div>
                <div class="col-md-4">
                    <label>Purchase Price</label>
                    <input type="number" class="form-control" id="purchase_price" placeholder="0.00" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Stock</label>
                    <input type="number" class="form-control" id="stocks" placeholder="0" required>
                </div>
                <div class="col-md-6">
                    <label>Store</label>
                    <select id="store_id" class="form-select" required>
                        <option value="">Loading stores...</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Material</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='/raw-material'">
                    Back
                </button>
            </div>
            
        </form>
    </div>
</div>

<script>
let stores = [];
// Fetch stores
fetch("/stores-json")
    .then(res => res.json())
    .then(data => {
        const storeSelect = document.getElementById("store_id");
        storeSelect.innerHTML = `<option value="">Select Store</option>` +
            data.map(s => `<option value="${s.id}">${s.store_name}</option>`).join('');
    })
    .catch(err => {
        document.getElementById("store_id").innerHTML = '<option value="">Failed to load stores</option>';
        console.error(err);
    });

// Submit form
document.getElementById("rawMaterialForm").addEventListener("submit", function(e){
    e.preventDefault();

    const data = {
        material_code: document.getElementById("material_code").value,
        material_name: document.getElementById("material_name").value,
        unit: document.getElementById("unit").value,
        packing: document.getElementById("packing").value,
        purchase_price: document.getElementById("purchase_price").value,
        stocks: document.getElementById("stocks").value,
        store_id: document.getElementById("store_id").value
    };

    fetch("/api/raw-material", { // make sure URL matches route
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data)
})
.then(res => res.json())
.then(resp => {
    if(resp.success){
        alert(resp.message);
        location.href = "/raw-material/create";
    } else {
        alert("Error: " + resp.message);
    }
})
.catch(err => alert("Request failed: " + err));
});
</script>
</body>
</html>
