@extends('layouts.app')

@section('body_class', 'page-banks')

@section('title', 'Bank Details')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold" style="color: #333;">
                        Bank Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bank Name:</label>
                        <p class="form-control-plaintext">{{ $bank->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Number:</label>
                        <p class="form-control-plaintext">{{ $bank->account }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p class="form-control-plaintext">
                            <span class="{{ $bank->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                {{ ucfirst($bank->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Created:</label>
                        <p class="form-control-plaintext">{{ $bank->created_at->format('M d, Y - h:i A') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p class="form-control-plaintext">{{ $bank->updated_at->format('M d, Y - h:i A') }}</p>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('banks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
