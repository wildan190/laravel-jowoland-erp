@extends('layouts.app')

@section('title', 'Tambah Quotation')

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('crm.quotations.index') }}">Quotation</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Quotation</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fa fa-file-invoice me-1"></i> Form Tambah Quotation
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('crm.quotations.store') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Header Info --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="quotationNumber" class="form-label">No. Quotation</label>
                            <input type="text" id="quotationNumber" class="form-control" value="-- Pilih Kategori --"
                                   readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="quotationDate" class="form-label">Tanggal</label>
                            <input type="date" id="quotationDate" class="form-control" value="{{ $quotationDate }}"
                                   readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="contact_id" class="form-label">Customer</label>
                            <select name="contact_id" id="contact_id" class="form-select" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($contacts as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="categorySelect" class="form-label">Kategori</label>
                            <select name="category" id="categorySelect" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="hydraulic">Hydraulic Rig (PHR)</option>
                                <option value="mini_crane">Mini Crane (PHM)</option>
                                <option value="strauss">Strauss Pile (PHS)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa fa-list me-1"></i> Item Layanan
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm align-middle" id="itemsTable">
                                <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 20%">Layanan</th>
                                    <th style="width: 25%">Deskripsi</th>
                                    <th style="width: 10%">Qty</th>
                                    <th style="width: 10%">Satuan</th>
                                    <th style="width: 15%">Harga</th>
                                    <th style="width: 15%">Jumlah</th>
                                    <th style="width: 5%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="items[0][item]"
                                               class="form-control form-control-sm"
                                               placeholder="Nama Layanan" required>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][description]"
                                               class="form-control form-control-sm"
                                               placeholder="Deskripsi">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][qty]"
                                               class="form-control form-control-sm qty text-end"
                                               value="1" min="0">
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][satuan]"
                                               class="form-control form-control-sm satuan text-end"
                                               placeholder="Unit" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[0][price]"
                                               class="form-control form-control-sm price text-end"
                                               placeholder="0" min="1000" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[0][total]"
                                               class="form-control form-control-sm lineTotal text-end"
                                               value="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger removeRow">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button" id="addRow" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus me-1"></i> Tambah Layanan
                            </button>
                        </div>
                    </div>

                    {{-- Terms Editor --}}
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa fa-file-text me-1"></i> Syarat dan Ketentuan
                        </div>
                        <div class="card-body">
                            <div id="termsEditor" style="height: 200px;"></div>
                            <input type="hidden" name="items[0][terms]" id="termsInput">
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-bold">
                            <i class="fa fa-calculator me-1"></i> Ringkasan
                        </div>
                        <div class="card-body row">
                            <div class="col-md-4 offset-md-8">
                                <div class="mb-2 d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <span id="subtotalText">0</span>
                                    <input type="hidden" name="subtotal" id="subtotalInput">
                                </div>
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <span>PPN (11%)</span>
                                    <div>
                                        <input type="checkbox" id="includePpn" name="include_ppn" checked>
                                        <label for="includePpn" class="ms-2">Include PPN</label>
                                    </div>
                                    <span id="ppnText">0</span>
                                    <input type="hidden" name="ppn" id="ppnInput">
                                </div>
                                <div class="mb-2 d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span id="totalText">0</span>
                                    <input type="hidden" name="total" id="totalInput">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('crm.quotations.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i> Simpan Quotation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        let row = 1;

        // Initialize Quill editor
        const quill = new Quill('#termsEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Update hidden input with Quill content
        quill.on('text-change', function() {
            document.getElementById('termsInput').value = quill.root.innerHTML;
        });

        // --- Toggle readonly state of lineTotal based on qty ---
        function toggleLineTotalReadonly(tr) {
            const qtyInput = tr.querySelector('.qty');
            const lineTotalInput = tr.querySelector('.lineTotal');
            if (parseFloat(qtyInput.value) === 0) {
                lineTotalInput.removeAttribute('readonly');
            } else {
                lineTotalInput.setAttribute('readonly', true);
            }
        }

        // --- Perhitungan Total ---
        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
                const qty = parseFloat(tr.querySelector('.qty')?.value) || 0;
                const price = parseFloat(tr.querySelector('.price')?.value) || 0;
                const lineTotalInput = tr.querySelector('.lineTotal');
                let lineTotal;

                if (qty === 0) {
                    lineTotal = parseFloat(lineTotalInput.value) || 0;
                } else {
                    lineTotal = qty * price;
                    lineTotalInput.value = lineTotal.toFixed(2);
                }

                subtotal += lineTotal;
            });

            let ppn = 0;
            if (document.getElementById('includePpn').checked) {
                ppn = subtotal * 0.11;
            }
            let total = subtotal + ppn;

            document.getElementById('subtotalText').innerText = subtotal.toLocaleString();
            document.getElementById('ppnText').innerText = ppn.toLocaleString();
            document.getElementById('totalText').innerText = total.toLocaleString();

            document.getElementById('subtotalInput').value = subtotal;
            document.getElementById('ppnInput').value = ppn;
            document.getElementById('totalInput').value = total;
        }

        // --- Tambah Row ---
        document.getElementById('addRow').addEventListener('click', function () {
            let tbody = document.querySelector('#itemsTable tbody');
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="text" name="items[${row}][item]" 
                           class="form-control form-control-sm" 
                           placeholder="Nama Layanan" required>
                </td>
                <td>
                    <input type="text" name="items[${row}][description]" 
                           class="form-control form-control-sm" 
                           placeholder="Deskripsi">
                </td>
                <td>
                    <input type="number" name="items[${row}][qty]" 
                           class="form-control form-control-sm qty text-end" 
                           value="1" min="0">
                </td>
                <td>
                    <input type="text" name="items[${row}][satuan]" 
                           class="form-control form-control-sm satuan text-end" 
                           placeholder="Unit" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${row}][price]" 
                           class="form-control form-control-sm price text-end" 
                           placeholder="0" min="1000" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${row}][total]" 
                           class="form-control form-control-sm lineTotal text-end" 
                           value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            toggleLineTotalReadonly(tr);
            row++;
        });

        // --- Event Handler ---
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty') || 
                e.target.classList.contains('price') || 
                e.target.classList.contains('lineTotal') || 
                e.target.id === 'includePpn') {
                const tr = e.target.closest('tr');
                if (tr && e.target.classList.contains('qty')) {
                    toggleLineTotalReadonly(tr);
                }
                calculateTotals();
            }
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.removeRow')) {
                e.target.closest('tr').remove();
                calculateTotals();
            }
        });

        // --- Preview Nomor Quotation ---
        function toRoman(num) {
            const map = {
                1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI',
                7: 'VII', 8: 'VIII', 9: 'IX', 10: 'X', 11: 'XI', 12: 'XII'
            };
            return map[num] || '';
        }

        document.getElementById('categorySelect').addEventListener('change', function () {
            let category = this.value;
            let code = category === 'hydraulic' ? 'PHR' : (category === 'mini_crane' ? 'PHM' : 'PHS');
            let today = new Date();
            let day = today.getDate().toString().padStart(2, '0');
            let monthRoman = toRoman(today.getMonth() + 1);
            let year = 2025;

            let preview = `BOQ-XXX/${day}/${code}/JLC/${monthRoman}/${year}`;
            document.getElementById('quotationNumber').value = preview;
        });

        // initial calc
        calculateTotals();
    </script>
@endsection