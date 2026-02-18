@extends('layouts.app')

@section('body_class', 'page-suppliers')

@section('title', 'Add New Supplier')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Supplier Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Add New Supplier
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf

                        <!-- Supplier Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
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

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input
                                type="text"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                id="phone_number"
                                name="phone_number"
                                value="{{ old('phone_number') }}"
                            />
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bank Name -->
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('bank_name') is-invalid @enderror"
                                id="bank_name"
                                name="bank_name"
                                value="{{ old('bank_name') }}"
                                required

                            />
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Account Number -->
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number<span class="text-danger"> *</span></label>
                            <input
                                type="text"
                                class="form-control @error('account_number') is-invalid @enderror"
                                id="account_number"
                                name="account_number"
                                value="{{ old('account_number') }}"
                                required
                            />
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Supplier
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
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