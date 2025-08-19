<div class="mb-3">
    <label class="form-label">Pilih Invoice</label>
    <select name="invoice_id" id="invoice-select" 
        class="form-select @error('invoice_id') is-invalid @enderror">
        <option value="">-- Pilih Invoice --</option>
        @foreach($invoices as $invoice)
            <option value="{{ $invoice->id }}"
                data-client="{{ $invoice->project->contact->name }}"
                data-client-id="{{ $invoice->project->contact->id }}"
                data-amount="{{ $invoice->grand_total }}"
                {{ old('invoice_id', $income->invoice_id ?? '') == $invoice->id ? 'selected' : '' }}>
                {{ $invoice->invoice_number }} - {{ $invoice->project->contact->name }} 
                (Rp {{ number_format($invoice->grand_total, 0, ',', '.') }})
            </option>
        @endforeach
    </select>
    @error('invoice_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

{{-- Hidden untuk ID contact --}}
<input type="hidden" name="contact_id" id="contact-id" 
    value="{{ old('contact_id', $income->contact_id ?? '') }}">

<div class="mb-3">
    <label class="form-label">Client</label>
    <input type="text" id="client-name" class="form-control" 
        value="{{ old('client_name', $income->contact->name ?? '') }}" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Jumlah</label>
    <input type="number" name="amount" id="amount" 
        class="form-control @error('amount') is-invalid @enderror"
        value="{{ old('amount', $income->amount ?? '') }}" readonly>
    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="date" 
        value="{{ old('date', isset($income->date) ? \Carbon\Carbon::parse($income->date)->format('Y-m-d') : '') }}" 
        class="form-control @error('date') is-invalid @enderror">
    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="description" 
        class="form-control @error('description') is-invalid @enderror">{{ old('description', $income->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const invoiceSelect = document.getElementById('invoice-select');
    const clientInput = document.getElementById('client-name');
    const contactIdInput = document.getElementById('contact-id');
    const amountInput = document.getElementById('amount');

    if (invoiceSelect.value) {
        const selected = invoiceSelect.options[invoiceSelect.selectedIndex];
        clientInput.value = selected.dataset.client || '';
        contactIdInput.value = selected.dataset.clientId || '';
        amountInput.value = selected.dataset.amount || '';
    }

    invoiceSelect.addEventListener('change', function () {
        const selected = invoiceSelect.options[invoiceSelect.selectedIndex];
        clientInput.value = selected.dataset.client || '';
        contactIdInput.value = selected.dataset.clientId || '';
        amountInput.value = selected.dataset.amount || '';
    });
});

</script>
