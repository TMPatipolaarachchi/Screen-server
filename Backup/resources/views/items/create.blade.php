@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Add New Item')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Item Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Add New Item
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('items.store') }}">
                        @csrf

                        <!-- Item Code -->
                        <div class="mb-3">
                            <label for="item_code" class="form-label">Item Code</label>
                            <input
                                type="text"
                                class="form-control"
                                id="item_code"
                                name="item_code"
                                value="{{ old('item_code', 'ITM-0001') }}"
                                readonly
                                disabled
                            />
                            <small class="form-text text-muted">This code will be auto-generated upon saving.</small>
                        </div>

                        <!-- Item Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                            />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id"
                                required
                            >
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cost Price -->
                        <div class="mb-3">
                            <label for="cost_price" class="form-label">Cost Price (LKR) <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.00001"
                                min="0"
                                class="form-control @error('cost_price') is-invalid @enderror"
                                id="cost_price"
                                name="cost_price"
                                value="{{ old('cost_price') }}"
                                required
                            />
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Selling Price -->
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price (LKR) <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.00001"
                                min="0"
                                class="form-control @error('selling_price') is-invalid @enderror"
                                id="selling_price"
                                name="selling_price"
                                value="{{ old('selling_price') }}"
                                required
                            />
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- VAT Available -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input @error('vat_available') is-invalid @enderror"
                                    id="vat_available"
                                    name="vat_available"
                                    value="1"
                                    {{ old('vat_available') ? 'checked' : '' }}
                                />
                                <label class="form-check-label" for="vat_available">
                                    VAT Available for this item
                                </label>
                                @error('vat_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Item
                            </button>
                            <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
