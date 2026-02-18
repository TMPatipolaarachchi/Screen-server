@extends('layouts.app')

@section('body_class', 'page-vehicles')

@section('title', 'Add Vehicle')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-black">Add New Vehicle</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 fw-bold">Vehicle Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('vehicles.store') }}" method="POST" id="vehicleForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_code" class="form-label">Vehicle Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_code') is-invalid @enderror" 
                                   id="vehicle_code" name="vehicle_code" value="{{ $vehicleCode }}" readonly>
                            <small class="form-text text-muted">Auto-generated code</small>
                            @error('vehicle_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                   id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" 
                                   placeholder="Enter vehicle number" required autofocus>
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            Save Vehicle
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
