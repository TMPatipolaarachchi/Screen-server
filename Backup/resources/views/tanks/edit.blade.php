@extends('layouts.app')

@section('body_class', 'page-tanks')

@section('title', 'Edit Tank')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Edit Tank Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Edit Tank
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tanks.update', $tank) }}">
                        @csrf
                        @method('PUT')

                        <!-- Item -->
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('item_id') is-invalid @enderror"
                                id="item_id"
                                name="item_id"
                                required
                                autofocus
                            >
                                <option value="">Select Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id', $tank->item_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tank Name -->
                        <div class="mb-3">
                            <label for="tank_name" class="form-label">Tank Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('tank_name') is-invalid @enderror"
                                id="tank_name"
                                name="tank_name"
                                value="{{ old('tank_name', $tank->tank_name) }}"
                                required
                            />
                            @error('tank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Max Stock -->
                        <div class="mb-3">
                            <label for="max_stock" class="form-label">Max Stock <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('max_stock') is-invalid @enderror"
                                id="max_stock"
                                name="max_stock"
                                value="{{ old('max_stock', $tank->max_stock) }}"
                                required
                            />
                            @error('max_stock')
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
                                <option value="active" {{ old('status', $tank->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tank->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="submit" class="btn btn-primary">
                                Update Tank
                            </button>
                            <a href="{{ route('tanks.index') }}" class="btn btn-secondary me-md-2">
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