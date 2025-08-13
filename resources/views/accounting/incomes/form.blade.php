<div class="mb-3">
    <label class="form-label">Pilih Deal</label>
    <select name="deal_id" id="deal-select" 
        class="form-select @error('deal_id') is-invalid @enderror">
        <option value="">-- Pilih Deal --</option>
        @foreach($deals as $deal)
            <option value="{{ $deal->id }}"
                data-client="{{ $deal->contact->name }}"
                data-client-id="{{ $deal->contact->id }}"
                data-amount="{{ $deal->value }}"
                {{ old('deal_id', $income->deal_id ?? '') == $deal->id ? 'selected' : '' }}>
                {{ $deal->contact->name }} - {{ $deal->title }} (Rp {{ number_format($deal->value, 0, ',', '.') }})
            </option>
        @endforeach
    </select>
    @error('deal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    const dealSelect = document.getElementById('deal-select');
    const clientInput = document.getElementById('client-name');
    const contactIdInput = document.getElementById('contact-id');
    const amountInput = document.getElementById('amount');

    // Set data saat halaman load (untuk edit)
    if (dealSelect.value) {
        const selected = dealSelect.options[dealSelect.selectedIndex];
        clientInput.value = selected.dataset.client || '';
        contactIdInput.value = selected.dataset.clientId || '';
        amountInput.value = selected.dataset.amount || '';
    }

    // Set data saat pilihan berubah
    dealSelect.addEventListener('change', function () {
        const selected = dealSelect.options[dealSelect.selectedIndex];
        clientInput.value = selected.dataset.client || '';
        contactIdInput.value = selected.dataset.clientId || '';
        amountInput.value = selected.dataset.amount || '';
    });
});
</script>
