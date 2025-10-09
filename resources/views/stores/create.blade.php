<form action="{{ route('stores.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Store Name</label>
        <input type="text" name="store_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control">
    </div>
    <div class="mb-3">
        <label>Phone Number</label>
        <input type="text" name="phone_number" class="form-control">
    </div>
    <div class="mb-3">
        <label>Manager ID</label>
        <input type="number" name="manager_id" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Add Store</button>
</form>
