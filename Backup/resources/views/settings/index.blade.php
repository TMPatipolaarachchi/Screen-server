@extends('layouts.app')

@section('body_class', 'page-settings')

@section('title', 'System Settings')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">System Settings</h2>
            <p class="text-muted mb-0">Manage system-wide configurations</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 fw-bold">VAT Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="vat_percentage" class="form-label">VAT Percentage (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('vat_percentage') is-invalid @enderror" 
                                       id="vat_percentage" 
                                       name="vat_percentage" 
                                       value="{{ old('vat_percentage', $vatPercentage) }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.00001"
                                       required>
                                <span class="input-group-text">%</span>
                                @error('vat_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                           
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Changing the VAT percentage here will only affect new invoices. Existing invoices will retain their original VAT values.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
