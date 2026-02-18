@extends('layouts.app')

@section('body_class', 'page-pumps')

@section('title', 'Add New Pump')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Pump Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Add New Pump
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pumps.store') }}">
                        @csrf

                        <!-- Pump Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Pump Name <span class="text-danger">*</span></label>
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

                        <!-- Tank -->
                        <div class="mb-3">
                            <label for="tank_id" class="form-label">Tank <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('tank_id') is-invalid @enderror"
                                id="tank_id"
                                name="tank_id"
                                required
                            >
                                <option value="">Select Tank</option>
                                @foreach ($tanks as $tank)
                                    <option value="{{ $tank->id }}" {{ old('tank_id') == $tank->id ? 'selected' : '' }}>
                                        {{ $tank->tank_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tank_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meter 1 -->
                        <div class="mb-3">
                            <label for="meter1_id" class="form-label">Meter 1 <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('meter1_id') is-invalid @enderror"
                                id="meter1_id"
                                name="meter1_id"
                                required
                            >
                                <option value="">Select Meter 1</option>
                                @foreach ($meters as $meter)
                                    <option value="{{ $meter->id }}" {{ old('meter1_id') == $meter->id ? 'selected' : '' }}>
                                        {{ $meter->meter_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meter1_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meter 2 -->
                        <div class="mb-3">
                            <label for="meter2_id" class="form-label">Meter 2 (Optional)</label>
                            <select
                                class="form-select @error('meter2_id') is-invalid @enderror"
                                id="meter2_id"
                                name="meter2_id"
                            >
                                <option value="">Select Meter 2</option>
                                @foreach ($meters as $meter)
                                    <option value="{{ $meter->id }}" {{ old('meter2_id') == $meter->id ? 'selected' : '' }}>
                                        {{ $meter->meter_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meter2_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="submit" class="btn btn-primary">
                                Create Pump
                            </button>
                            <a href="{{ route('pumps.index') }}" class="btn btn-secondary me-md-2">
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