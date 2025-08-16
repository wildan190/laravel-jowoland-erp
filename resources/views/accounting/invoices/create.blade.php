@extends('layouts.app')

@section('title', 'Tambah Invoice')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('accounting.invoices.index') }}">Invoices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Invoice</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary"><i class="fa fa-file-invoice-dollar me-2"></i> Tambah Invoice</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('accounting.invoices.store') }}" method="POST">
                @csrf

                {{-- Select Project --}}
                <div class="mb-3">
                    <label for="project_id" class="form-label fw-semibold">Project</label>
                    <select name="project_id" id="project_id" class="form-select" required>
                        <option value="">-- Pilih Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" data-amount="{{ $project->deal->value ?? 0 }}">
                                {{ $project->name }} ({{ $project->contact->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Project Amount --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Project Amount</label>
                    <input type="text" id="project_amount_display" class="form-control" readonly>
                    <input type="hidden" name="project_amount" id="project_amount">
                </div>

                {{-- Services --}}
                <h5 class="text-dark mt-4 mb-2">Services</h5>
                <div id="items"></div>
                <button type="button" class="btn btn-outline-secondary mb-3" id="add-item">+ Tambah Service</button>

                {{-- Subtotal --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Subtotal</label>
                    <input type="text" id="subtotal_display" class="form-control" readonly>
                    <input type="hidden" name="subtotal" id="subtotal">
                </div>

                {{-- Tax --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">PPN (11%)</label>
                    <input type="text" id="tax_display" class="form-control" readonly>
                    <input type="hidden" name="tax" id="tax">
                </div>

                {{-- Grand Total --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Grand Total</label>
                    <input type="text" id="grand_total_display" class="form-control" readonly>
                    <input type="hidden" name="grand_total" id="grand_total">
                </div>

                {{-- Due Date --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>

                {{-- Tombol Simpan --}}
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('accounting.invoices.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
let itemIndex = 0;

function recalcTotal() {
    let projectAmount = parseFloat(document.getElementById('project_amount').value) || 0;
    let itemsTotal = 0;

    document.querySelectorAll('.item-price').forEach(el => {
        itemsTotal += parseFloat(el.value) || 0;
    });

    let subtotal = projectAmount + itemsTotal;
    let tax = subtotal * 0.11;
    let grandTotal = subtotal + tax;

    document.getElementById('subtotal_display').value = subtotal.toLocaleString('id-ID', {minimumFractionDigits: 2});
    document.getElementById('tax_display').value = tax.toLocaleString('id-ID', {minimumFractionDigits: 2});
    document.getElementById('grand_total_display').value = grandTotal.toLocaleString('id-ID', {minimumFractionDigits: 2});

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('tax').value = tax.toFixed(2);
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

// Update project amount
document.getElementById('project_id').addEventListener('change', function() {
    let amount = this.options[this.selectedIndex].getAttribute('data-amount');
    document.getElementById('project_amount_display').value = parseFloat(amount).toLocaleString('id-ID', {minimumFractionDigits: 2});
    document.getElementById('project_amount').value = amount;
    recalcTotal();
});

// Tambah service
document.getElementById('add-item').addEventListener('click', function() {
    let itemsDiv = document.getElementById('items');
    let row = document.createElement('div');
    row.classList.add('d-flex', 'mb-2', 'gap-2', 'align-items-center');
    row.innerHTML = `
        <input type="text" name="items[${itemIndex}][description]" placeholder="Service description" class="form-control" required>
        <input type="number" step="0.01" name="items[${itemIndex}][price]" placeholder="Price" class="form-control item-price" required>
        <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
    `;
    itemsDiv.appendChild(row);
    itemIndex++;
});

// Event delegation untuk update total
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('item-price')) {
        recalcTotal();
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('div').remove();
        recalcTotal();
    }
});
</script>
@endsection
