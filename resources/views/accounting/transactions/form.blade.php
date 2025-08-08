<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input type="date" name="date" value="{{ old('date', $transaction->date ?? '') }}" class="form-control @error('date') is-invalid @enderror">
    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Jenis</label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" id="transaction-type">
        <option value="">-- Pilih Jenis --</option>
        <option value="income" {{ old('type', $transaction->type ?? '') == 'income' ? 'selected' : '' }}>Pemasukan</option>
        <option value="expense" {{ old('type', $transaction->type ?? '') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
    </select>
    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<input type="hidden" name="amount" id="transaction-amount" value="{{ old('amount', $transaction->amount ?? '') }}">

<div class="mb-3 income-field d-none">
    <label class="form-label">Pilih Pemasukan</label>
    <select name="income_id" class="form-select @error('income_id') is-invalid @enderror" id="income-select">
        <option value="">-- Pilih --</option>
        @foreach($incomes as $inc)
            <option value="{{ $inc->id }}" data-amount="{{ $inc->amount }}"
                {{ old('income_id', $transaction->income_id ?? '') == $inc->id ? 'selected' : '' }}>
                {{ $inc->description }} - Rp {{ number_format($inc->amount, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
    @error('income_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3 expense-field d-none">
    <label class="form-label">Pilih Pembelian</label>
    <select name="purchasing_id" class="form-select @error('purchasing_id') is-invalid @enderror" id="purchasing-select">
        <option value="">-- Pilih --</option>
        @foreach($purchasings as $pur)
            <option value="{{ $pur->id }}" data-amount="{{ $pur->total_price }}"
                {{ old('purchasing_id', $transaction->purchasing_id ?? '') == $pur->id ? 'selected' : '' }}>
                {{ $pur->item_name }} - Rp {{ number_format($pur->total_price, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
    @error('purchasing_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('transaction-type');
    const incomeField = document.querySelector('.income-field');
    const expenseField = document.querySelector('.expense-field');
    const incomeSelect = document.getElementById('income-select');
    const purchasingSelect = document.getElementById('purchasing-select');
    const amountInput = document.getElementById('transaction-amount');

    function toggleFields() {
        incomeField.classList.add('d-none');
        expenseField.classList.add('d-none');
        if (typeSelect.value === 'income') {
            incomeField.classList.remove('d-none');
        }
        if (typeSelect.value === 'expense') {
            expenseField.classList.remove('d-none');
        }
    }

    function updateAmountFromIncome() {
        const selected = incomeSelect.options[incomeSelect.selectedIndex];
        amountInput.value = selected.dataset.amount || '';
    }

    function updateAmountFromPurchasing() {
        const selected = purchasingSelect.options[purchasingSelect.selectedIndex];
        amountInput.value = selected.dataset.amount || '';
    }

    typeSelect.addEventListener('change', toggleFields);
    incomeSelect.addEventListener('change', updateAmountFromIncome);
    purchasingSelect.addEventListener('change', updateAmountFromPurchasing);

    toggleFields();
    updateAmountFromIncome();
    updateAmountFromPurchasing();
});
</script>
