@extends('layouts.app')

@section('body_class', 'page-vehicles')

@section('title', 'Edit Vehicle')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-black">Edit Vehicle</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold">Vehicle Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" id="vehicleForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_code" class="form-label">Vehicle Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="vehicle_code" value="{{ $vehicle->vehicle_code }}" readonly>
                            <small class="form-text text-muted">Cannot be changed</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                   id="vehicle_number" name="vehicle_number" 
                                   value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" 
                                   placeholder="Enter vehicle number" required autofocus>
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                             Update Vehicle
                        </button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
