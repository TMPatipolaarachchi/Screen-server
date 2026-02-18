@extends('layouts.app')

@section('body_class', 'page-meters')

@section('title', 'Edit Meter')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Edit Meter Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Edit Meter
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('meters.update', $meter) }}">
                        @csrf
                        @method('PUT')

                        <!-- Meter Name -->
                        <div class="mb-3">
                            <label for="meter_name" class="form-label">Meter Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('meter_name') is-invalid @enderror"
                                id="meter_name"
                                name="meter_name"
                                value="{{ old('meter_name', $meter->meter_name) }}"
                                required
                                autofocus
                            />
                            @error('meter_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item -->
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Item <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('item_id') is-invalid @enderror"
                                id="item_id"
                                name="item_id"
                                required
                            >
                                <option value="">-- Select Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" @selected(old('item_id', $meter->item_id) == $item->id)>
                                        {{ $item->name }} - Rs. {{ number_format($item->cost_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meter Value -->
                        <div class="mb-3">
                            <label for="meter_value" class="form-label">Meter Value <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.001"
                                class="form-control @error('meter_value') is-invalid @enderror"
                                id="meter_value"
                                name="meter_value"
                                value="{{ old('meter_value', $meter->meter_value) }}"
                                required
                            />
                            @error('meter_value')
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
                                <option value="active" {{ old('status', $meter->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $meter->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="submit" class="btn btn-primary">
                                Save Meter
                            </button>
                            <a href="{{ route('meters.index') }}" class="btn btn-secondary me-md-2">
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