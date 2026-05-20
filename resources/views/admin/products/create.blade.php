@extends('admin.layouts.app')
@section('title', 'Create Product')
@section('page_title', 'Products / Create')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Create Product</h1>
        <p class="page-subtitle">Add a new product to the catalogue</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-outline-dim">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@if($errors->any())
<div class="alert-dark alert-error-dark">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
        <strong>Please fix the following errors:</strong>
        <ul style="margin:6px 0 0 16px;padding:0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-4">
    {{-- Left column: product info --}}
    <div class="col-12 col-lg-8">

        {{-- Basic info --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark">Product Information</div>
            <div class="card-body-dark">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-dark" for="product_name">Product Name *</label>
                        <input type="text" id="product_name" name="product_name"
                               class="form-control-dark" required
                               value="{{ old('product_name') }}"
                               placeholder="e.g. Essential Heavyweight Tee">
                    </div>
                    <div class="col-6">
                        <label class="form-label-dark" for="category_id">Category *</label>
                        <select id="category_id" name="category_id" class="form-select-dark" required>
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-dark" for="base_price">Base Price (RM) *</label>
                        <input type="number" id="base_price" name="base_price"
                               class="form-control-dark" required step="0.01" min="0"
                               value="{{ old('base_price') }}" placeholder="0.00">
                    </div>
                    <div class="col-12">
                        <label class="form-label-dark" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control-dark"
                                  rows="4" placeholder="Product description...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label-dark">Status *</label>
                        <div style="display:flex;gap:16px;margin-top:4px;">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--text-secondary);">
                                <input type="radio" name="status" value="Active" {{ old('status','Active') === 'Active' ? 'checked' : '' }}>
                                <span class="badge-status badge-active">Active</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--text-secondary);">
                                <input type="radio" name="status" value="Inactive" {{ old('status') === 'Inactive' ? 'checked' : '' }}>
                                <span class="badge-status badge-inactive">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Variants --}}
        <div class="card-dark mb-4">
            <div class="card-header-dark" style="display:flex;justify-content:space-between;align-items:center;">
                <span>Variants (Size / Colour)</span>
                <button type="button" class="btn-outline-dim" id="addVariantBtn" style="font-size:11px;padding:4px 10px;">
                    <i class="bi bi-plus"></i> Add Variant
                </button>
            </div>
            <div class="card-body-dark" id="variantsContainer">

                {{-- Default variant row --}}
                <div class="variant-row" style="display:grid;grid-template-columns:1fr 1fr 1.5fr 80px 100px 36px;gap:10px;align-items:end;margin-bottom:12px;">
                    <div>
                        <label class="form-label-dark">Size *</label>
                        <select name="variants[0][size]" class="form-select-dark" required>
                            <option value="">Size</option>
                            @foreach(['XS','S','M','L','XL','XXL','XXXL','Free Size'] as $sz)
                                <option value="{{ $sz }}">{{ $sz }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-dark">Colour *</label>
                        <input type="text" name="variants[0][colour]" class="form-control-dark" placeholder="Black" required>
                    </div>
                    <div>
                        <label class="form-label-dark">SKU Code *</label>
                        <input type="text" name="variants[0][sku_code]" class="form-control-dark" placeholder="STW-TEE-BLK-M" required>
                    </div>
                    <div>
                        <label class="form-label-dark">Stock *</label>
                        <input type="number" name="variants[0][stock_qty]" class="form-control-dark" value="0" min="0" required>
                    </div>
                    <div>
                        <label class="form-label-dark">+Price (RM)</label>
                        <input type="number" name="variants[0][additional_price]" class="form-control-dark" value="0" step="0.01" min="0">
                    </div>
                    <div style="padding-bottom:1px;">
                        <button type="button" class="btn-danger-dim remove-variant" style="padding:8px;display:none;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Right column: images --}}
    <div class="col-12 col-lg-4">
        <div class="card-dark mb-4">
            <div class="card-header-dark">Product Images</div>
            <div class="card-body-dark">
                <label class="form-label-dark">Upload Images</label>
                <div id="dropzone" style="border:2px dashed var(--border);border-radius:4px;padding:32px;text-align:center;cursor:pointer;transition:.15s;color:var(--text-muted);" onclick="document.getElementById('imageInput').click()">
                    <i class="bi bi-cloud-upload" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                    <div style="font-size:13px;">Click to upload or drag & drop</div>
                    <div style="font-size:11px;margin-top:4px;">JPG, PNG, WEBP — max 2MB each</div>
                </div>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display:none;" onchange="previewImages(this)">

                <div id="imagePreview" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:12px;"></div>

                <div style="margin-top:12px;font-size:12px;color:var(--text-muted);">
                    <i class="bi bi-info-circle"></i> After upload, select which image to set as primary.
                </div>
            </div>
        </div>

        <div class="card-dark">
            <div class="card-body-dark">
                <button type="submit" class="btn-accent" style="width:100%;justify-content:center;padding:13px;">
                    <i class="bi bi-check2"></i> Save Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline-dim" style="width:100%;justify-content:center;padding:12px;margin-top:8px;">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let variantIndex = 1;

document.getElementById('addVariantBtn').addEventListener('click', function () {
    const container = document.getElementById('variantsContainer');
    const i = variantIndex++;

    const row = document.createElement('div');
    row.className = 'variant-row';
    row.style = 'display:grid;grid-template-columns:1fr 1fr 1.5fr 80px 100px 36px;gap:10px;align-items:end;margin-bottom:12px;';
    row.innerHTML = `
        <div>
            <label class="form-label-dark">Size *</label>
            <select name="variants[${i}][size]" class="form-select-dark" required>
                <option value="">Size</option>
                ${['XS','S','M','L','XL','XXL','XXXL','Free Size'].map(s => `<option value="${s}">${s}</option>`).join('')}
            </select>
        </div>
        <div>
            <label class="form-label-dark">Colour *</label>
            <input type="text" name="variants[${i}][colour]" class="form-control-dark" placeholder="Black" required>
        </div>
        <div>
            <label class="form-label-dark">SKU Code *</label>
            <input type="text" name="variants[${i}][sku_code]" class="form-control-dark" placeholder="STW-TEE-BLK-M" required>
        </div>
        <div>
            <label class="form-label-dark">Stock *</label>
            <input type="number" name="variants[${i}][stock_qty]" class="form-control-dark" value="0" min="0" required>
        </div>
        <div>
            <label class="form-label-dark">+Price (RM)</label>
            <input type="number" name="variants[${i}][additional_price]" class="form-control-dark" value="0" step="0.01" min="0">
        </div>
        <div style="padding-bottom:1px;">
            <button type="button" class="btn-danger-dim remove-variant" style="padding:8px;">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);

    // Show remove buttons if >1 variant
    updateRemoveButtons();
});

document.getElementById('variantsContainer').addEventListener('click', function(e) {
    if (e.target.closest('.remove-variant')) {
        e.target.closest('.variant-row').remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.variant-row');
    rows.forEach((row, i) => {
        const btn = row.querySelector('.remove-variant');
        if (btn) btn.style.display = rows.length > 1 ? 'flex' : 'none';
    });
}

// Image preview
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrapper = document.createElement('div');
            wrapper.style = 'position:relative;aspect-ratio:1;';
            wrapper.innerHTML = `
                <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:4px;border:1px solid var(--border);">
                <label style="position:absolute;bottom:4px;left:4px;right:4px;background:rgba(0,0,0,.7);color:#fff;font-size:10px;text-align:center;padding:3px;cursor:pointer;border-radius:2px;">
                    <input type="radio" name="primary_image" value="${index}" ${index === 0 ? 'checked' : ''} style="margin-right:3px;">Primary
                </label>
            `;
            preview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

// Drag & drop
const dropzone = document.getElementById('dropzone');
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = 'var(--accent)'; });
dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = 'var(--border)'; });
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.style.borderColor = 'var(--border)';
    const input = document.getElementById('imageInput');
    input.files = e.dataTransfer.files;
    previewImages(input);
});
</script>
@endpush
