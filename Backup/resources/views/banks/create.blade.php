@extends('layouts.app')

@section('body_class', 'page-banks')

@section('title', 'Add New Bank')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Bank Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Add New Bank
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('banks.store') }}">
                        @csrf

                        <!-- Bank Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Bank Name <span class="text-danger">*</span></label>
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

                        <!-- Bank Account -->
                        <div class="mb-3">
                            <label for="account" class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('account') is-invalid @enderror"
                                id="account"
                                name="account"
                                value="{{ old('account') }}"
                                required
                            />
                            <small class="form-text text-muted">Enter a unique account number</small>
                            @error('account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Bank
                            </button>
                            <a href="{{ route('banks.index') }}" class="btn btn-secondary">
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
