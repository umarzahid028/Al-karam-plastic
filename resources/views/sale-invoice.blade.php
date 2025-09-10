<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sale Invoice</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background: #f5f7fa; font-family: Arial, sans-serif; }
.invoice-box { background: rgba(255,255,255,0.95); border-radius: 12px; padding:25px; max-width:900px; margin:50px auto; box-shadow:0 6px 20px rgba(0,0,0,0.15);}
.small-muted { font-size:0.85rem; font-weight:500; color:#555;}
.btn-purple { background:#6366f1; color:white;}
.btn-purple:hover { background:#4f46e5; color:white;}
.list-group-item { cursor:pointer; }
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
<body>
<div class="invoice-box">
    <h4 class="mb-4">Sale Invoice</h4>

    <!-- Buyer / Salesperson / Payment -->
    <div class="row g-3 mb-3">
        <div class="col-md-5">
            <label class="form-label small-muted">Buyer</label>
            <select id="buyer" class="form-select">
                <option value="">Select Buyer</option>
                @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" data-contact="{{ $buyer->contact_no }}">
                        {{ $buyer->company_name }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <div class="col-md-3">
            <label class="form-label small-muted">Salesperson</label>
            <select id="salesperson" class="form-select">
                <option value="">Select</option>
                @foreach($salespersons as $salesperson)
                    <option value="{{ $salesperson->id }}">{{ $salesperson->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small-muted">Payment Method</label>
            <select id="paymentMethod" class="form-select">
                <option>Cash</option>
                <option>Bank Transfer</option>
                <option>Credit Card</option>
            </select>
        </div>
    </div>

    <!-- Invoice No / Date -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label small-muted">Invoice #</label>
            <input id="invoiceNo" type="text" class="form-control" value="{{ $invoiceNo }}">
        </div>
        <div class="col-md-6">
            <label class="form-label small-muted">Date</label>
            <input id="invoiceDate" type="date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
    </div>

    <!-- Add Product -->
    <h6 class="mb-2">Add Product</h6>
    <div class="row g-2 mb-3">
        <div class="col-md-2 position-relative">
            <input id="productId" class="form-control" placeholder="Product ID" autocomplete="off">
            <div id="productList" class="list-group position-absolute w-100" style="z-index:1000;"></div>
        </div>
        <div class="col-md-2 position-relative">
            <input id="name" class="form-control" placeholder="Name" autocomplete="off">
            <div id="nameList" class="list-group position-absolute w-100" style="z-index:1000;"></div>
        </div>
        <div class="col-md-2 position-relative">
            <input id="group" class="form-control" placeholder="Group" autocomplete="off">
            <div id="groupList" class="list-group position-absolute w-100" style="z-index:1000;"></div>
        </div>
        <div class="col-md-2">
            <input id="size" class="form-control" placeholder="Size">
        </div>
        <div class="col-md-1">
            <input id="qty" type="number" class="form-control" placeholder="Qty" min="1">
        </div>
        <div class="col-md-1">
            <input id="price" type="number" class="form-control" placeholder="Price" min="0">
        </div>
        <div class="col-md-1">
            <input id="weight" type="number" class="form-control" placeholder="W">
        </div>
        <div class="col-md-1 d-grid">
            <button id="addBtn" class="btn btn-purple">Add</button>
        </div>
    </div>

    <!-- Product Table -->
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th><th>Product ID</th><th>Name</th><th>Group</th>
                <th>Size</th><th>Qty</th><th>Price</th><th>Weight</th><th>Total</th><th>Delete</th>
            </tr>
        </thead>
        <tbody id="productTable"></tbody>
    </table>

    <!-- Totals -->
    <div class="mt-3">
        <p>Invoice Total: Rs <span id="invoiceTotal">0</span></p>
        <p>Previous Balance: Rs <span id="prevBalance">0</span></p>
        <h5>Grand Total: Rs <span id="grandTotal">0</span></h5>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-purple" id="generatePDF">Generate PDF</button>
        <button class="btn btn-success" id="submitBtn">Submit Invoice</button>
        <a class="btn btn-secondary" href="{{route('welcome')}}" >Back</a>
    </div>
</div>

<!-- Gate Pass Modal -->
<div class="modal fade" id="gatePassModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title">Gate Pass</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="gatePassContent">
        <!-- Dynamic Slip -->
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button id="savePassBtn" class="btn btn-secondary">Back</button>
        <button id="printPassBtn" class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>

<script>
let rowCount = 0, invoiceTotal = 0, prevBalance = 0;

// ================= Search =================
function setupSearch(inputId, listId, type){
    const input=document.getElementById(inputId);
    const list=document.getElementById(listId);

    input.addEventListener("keyup",function(){
        const q=this.value.trim();
        if(q.length<1){ list.innerHTML=""; return; }

        fetch(`/products/search?q=${q}&type=${type}`)
        .then(res=>res.json())
        .then(data=>{
            list.innerHTML="";
            data.forEach(p=>{
                const item=document.createElement("a");
                item.href="#";
                item.className="list-group-item list-group-item-action";
                item.textContent=`${p.product_code} - ${p.product_name} (${p.product_group})`;
                item.onclick=e=>{
                    e.preventDefault();
                    document.getElementById("productId").value=p.product_code;
                    document.getElementById("name").value=p.product_name;
                    document.getElementById("group").value=p.product_group;
                    document.getElementById("size").value=p.size;
                    document.getElementById("price").value=p.sale_price;
                    document.getElementById("weight").value=p.weight;
                    document.getElementById("qty").value=1;
                    list.innerHTML="";
                }
                list.appendChild(item);
            });
        });
    });
}

setupSearch("productId","productList","id");
setupSearch("name","nameList","name");
setupSearch("group","groupList","group");

// ================= Buyer Balance =================
function fetchBuyerBalance(buyerId){
    if(!buyerId){ prevBalance=0; document.getElementById("prevBalance").textContent="0"; updateTotals(); return; }

    fetch(`/buyers/${buyerId}/balance`)
    .then(res=>res.json())
    .then(data=>{
        prevBalance=parseFloat(data.balance)||0;
        document.getElementById("prevBalance").textContent=prevBalance.toFixed(2);
        updateTotals();
    });
}

document.getElementById("buyer").addEventListener("change", function(){ fetchBuyerBalance(this.value); });

// ================= Add Product =================
document.getElementById("addBtn").addEventListener("click", function(e){
    e.preventDefault();
    const productId=document.getElementById("productId").value.trim();
    const name=document.getElementById("name").value.trim();
    const group=document.getElementById("group").value.trim();
    const size=document.getElementById("size").value.trim();
    const qty=parseFloat(document.getElementById("qty").value)||0;
    const price=parseFloat(document.getElementById("price").value)||0;
    const weight=parseFloat(document.getElementById("weight").value)||0;

    if(!productId||!name||qty<=0||price<=0){ alert("Please fill Product ID, Name, Qty and Price"); return; }

    const total=qty*price;
    rowCount++;
    const table=document.getElementById("productTable");
    const row=document.createElement("tr");
    row.innerHTML=`
        <td>${rowCount}</td>
        <td>${productId}</td>
        <td>${name}</td>
        <td>${group}</td>
        <td>${size}</td>
        <td>${qty}</td>
        <td>${price.toFixed(2)}</td>
        <td>${weight}</td>
        <td class="row-total">${total.toFixed(2)}</td>
        <td><button class="btn btn-sm btn-danger deleteBtn">X</button></td>
    `;
    table.appendChild(row);
    invoiceTotal+=total;
    updateTotals();
    ["productId","name","group","size","qty","price","weight"].forEach(id=>document.getElementById(id).value="");
});

// ================= Delete Product =================
document.getElementById("productTable").addEventListener("click", function(e){
    if(e.target.classList.contains("deleteBtn")){
        const row=e.target.closest("tr");
        invoiceTotal -= parseFloat(row.querySelector(".row-total").textContent);
        row.remove();
        updateTotals();
    }
});

// ================= Submit Invoice =================
document.getElementById("submitBtn").addEventListener("click", function(e){
    e.preventDefault();
    const buyerId=document.getElementById("buyer").value;
    const invoiceNo=document.getElementById("invoiceNo").value.trim();
    const invoiceDate=document.getElementById("invoiceDate").value;

    if(!buyerId||!invoiceNo||!invoiceDate||invoiceTotal<=0){ alert("Fill all required fields"); return; }

    const items=[];
    document.querySelectorAll("#productTable tr").forEach(row=>{
        const cols=row.querySelectorAll("td");
        items.push({
            product_id: cols[1].textContent.trim(),
            qty: parseFloat(cols[5].textContent),
            price: parseFloat(cols[6].textContent),
            total: parseFloat(cols[8].textContent)
        });
    });

    fetch("/sales-invoices", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            buyer_id: parseInt(buyerId),
            salesperson_id: parseInt(document.getElementById("salesperson").value),
            payment_method: document.getElementById("paymentMethod").value,
            invoice_no: invoiceNo,
            invoice_date: invoiceDate,
            total_amount: parseFloat(invoiceTotal+prevBalance),
            remarks: "First Sale",
            items: items
        })
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            const buyerText=document.getElementById("buyer").selectedOptions[0]?.text||"";

            let productRows="";
            document.querySelectorAll("#productTable tr").forEach(row=>{
                const cols=row.querySelectorAll("td");
                productRows+=`
                    <tr>
                        <td>${cols[2].textContent}</td>
                        <td>${cols[5].textContent}</td>
                        <td>${cols[6].textContent}</td>
                        <td>${cols[8].textContent}</td>
                    </tr>
                `;
            });

            const slipHtml=`
            <div class="container">
              <div class="card shadow-lg position-relative p-3">
                <div class="card-header text-black text-center">
                  <h3>Gate Pass</h3>
                </div>
                <div class="card-body position-relative">
                  <div class="duplicate-badge" style="display:none;">DUPLICATE PASS</div>
                  <p><strong>Pass No:</strong> GP-NEW</p>
                  <p><strong>Invoice No:</strong> ${invoiceNo}</p>
                  <p><strong>User:</strong> Current User</p>
                  <p><strong>Total Items:</strong> ${document.querySelectorAll("#productTable tr").length}</p>
                  <p><strong>Customer:</strong> ${buyerText}</p>
                 <p><strong>Contact No:</strong> {{ $gatePass->invoice->buyer->contact_no ?? 'N/A' }}</p>

                </div>
                <h5 class="mt-4">Invoice Items</h5>
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr><th>Product Name</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                  </thead>
                  <tbody>${productRows}</tbody>
                </table>
               
              </div>
            </div>`;
            document.getElementById("gatePassContent").innerHTML=slipHtml;
            new bootstrap.Modal(document.getElementById("gatePassModal")).show();
        }
        else{ alert("Error: "+data.message); }
    });
});

// ================= Update Totals =================
function updateTotals(){
    document.getElementById("invoiceTotal").textContent=invoiceTotal.toFixed(2);
    document.getElementById("grandTotal").textContent=(invoiceTotal+prevBalance).toFixed(2);
}

// ================= Save Gate Pass =================
document.getElementById("savePassBtn").addEventListener("click", function(){
    const invoiceNo=document.getElementById("invoiceNo").value;
    fetch("/gate-passes", {
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ invoice_no: invoiceNo, status: "saved" })
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            alert("Gate pass saved successfully!");
            location.href="{{route('welcome')}}";
        }
    });
});

// ================= Print Gate Pass =================
document.getElementById("printPassBtn").addEventListener("click", function(){
    const content=document.getElementById("gatePassContent").innerHTML;
    const printWin=window.open("","","width=800,height=600");
    printWin.document.write(`<html><head><title>Gate Pass</title></head><body>${content}</body></html>`);
    printWin.document.close();
    printWin.print();
});

// ================= Generate PDF =================
document.getElementById("generatePDF").addEventListener("click", function(){
    const { jsPDF }=window.jspdf;
    const doc=new jsPDF();
    doc.setFontSize(18); doc.text("Sale Invoice",14,20);

    const buyerText=document.getElementById("buyer").selectedOptions[0]?.text||"";
    const salespersonText=document.getElementById("salesperson").selectedOptions[0]?.text||"";
    const invoiceNo=document.getElementById("invoiceNo").value;
    const invoiceDate=document.getElementById("invoiceDate").value;

    doc.setFontSize(12);
    doc.text(`Buyer: ${buyerText}`,14,30);
    doc.text(`Salesperson: ${salespersonText}`,14,36);
    doc.text(`Invoice #: ${invoiceNo}`,140,30);
    doc.text(`Date: ${invoiceDate}`,140,36);

    const headers=["#","Product ID","Name","Group","Size","Qty","Price","Weight","Total"];
    let startY=50;
    doc.setFontSize(10);
    headers.forEach((h,i)=>doc.text(h,14+i*20,startY));
    startY+=6;

    document.querySelectorAll("#productTable tr").forEach(row=>{
        const cols=row.querySelectorAll("td");
        Array.from(cols).slice(0,9).forEach((cell,i)=>doc.text(String(cell.textContent),14+i*20,startY));
        startY+=6;
    });

    startY+=6;
    doc.text(`Invoice Total: Rs ${invoiceTotal.toFixed(2)}`,14,startY);
    doc.text(`Previous Balance: Rs ${prevBalance.toFixed(2)}`,14,startY+6);
    doc.text(`Grand Total: Rs ${(invoiceTotal+prevBalance).toFixed(2)}`,14,startY+12);

    doc.save(`Invoice-${invoiceNo||"0001"}.pdf`);
});
</script>
</body>
</html>
