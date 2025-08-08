<div class="mb-3">
    <label class="form-label">Pilih Deal</label>
    <select name="deal_id" id="deal-select" class="form-select @error('deal_id') is-invalid @enderror">
        <option value="">-- Pilih Deal --</option>
        @foreach($deals as $deal)
            <option value="{{ $deal->id }}"
                data-client="{{ $deal->contact->name }}"
                data-amount="{{ $deal->value }}">
                {{ $deal->contact->name }} - {{ $deal->title }} (Rp {{ number_format($deal->value, 0, ',', '.') }})
            </option>
        @endforeach
    </select>
    @error('deal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Client</label>
    <input type="text" id="client-name" name="contact_id" class="form-control" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Jumlah</label>
    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" readonly>
    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="date" value="{{ old('date', $income->date ?? '') }}" class="form-control @error('date') is-invalid @enderror">
    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $income->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dealSelect = document.getElementById('deal-select');
    const clientInput = document.getElementById('client-name');
    const amountInput = document.getElementById('amount');

    dealSelect.addEventListener('change', function () {
        const selected = dealSelect.options[dealSelect.selectedIndex];
        clientInput.value = selected.dataset.client || '';
        amountInput.value = selected.dataset.amount || '';
    });
});
</script>
