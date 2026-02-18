@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Edit Item')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Edit Item Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">   
                        Edit Item
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('items.update', $item->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Item Code -->
                        <div class="mb-3">
                            <label for="item_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('item_code') is-invalid @enderror"
                                id="item_code"
                                name="item_code"
                                value="{{ old('item_code', $item->item_code) }}"
                                required
                                autofocus
                            />
                            <small class="form-text text-muted">Enter a unique code (e.g., ITM001, ITM002)</small>
                            @error('item_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $item->name) }}"
                                required
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
                                    <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>
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
                                value="{{ old('cost_price', $item->cost_price) }}"
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
                                value="{{ old('selling_price', $item->selling_price) }}"
                                required
                            />
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required
                            >
                                <option value="">-- Select Status --</option>
                                <option value="active" @selected(old('status', $item->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $item->status) === 'inactive')>Inactive</option>
                            </select>
                            @error('status')
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
                                    {{ old('vat_available', $item->vat_available) ? 'checked' : '' }}
                                />
                                <label class="form-check-label" for="vat_available">
                                    VAT Available for this item
                                </label>
                                @error('vat_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Check this if VAT should be calculated for this item when creating invoices.
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save changes
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
