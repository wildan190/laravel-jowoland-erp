<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="date" value="{{ old('date', $purchasing->date ?? '') }}" class="form-control @error('date') is-invalid @enderror">
    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Nama Barang</label>
    <input type="text" name="item_name" value="{{ old('item_name', $purchasing->item_name ?? '') }}" class="form-control @error('item_name') is-invalid @enderror">
    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Harga Satuan</label>
    <input type="number" id="unit_price" name="unit_price" value="{{ old('unit_price', $purchasing->unit_price ?? '') }}" class="form-control @error('unit_price') is-invalid @enderror">
    @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Jumlah</label>
    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $purchasing->quantity ?? '') }}" class="form-control @error('quantity') is-invalid @enderror">
    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Total</label>
    <input type="number" id="total" name="total" value="{{ old('total', $purchasing->total ?? '') }}" class="form-control" readonly>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateTotal() {
        let unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
        let quantity = parseInt(document.getElementById('quantity').value) || 0;
        document.getElementById('total').value = unitPrice * quantity;
    }
    document.getElementById('unit_price').addEventListener('input', updateTotal);
    document.getElementById('quantity').addEventListener('input', updateTotal);
});
</script>
