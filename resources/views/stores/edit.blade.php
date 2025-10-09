<form action="{{ route('stores.update', $store) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ $store->status=='active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $store->status=='inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
</form>
