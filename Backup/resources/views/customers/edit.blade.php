@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Edit Customer')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Edit Customer Section -->
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Edit Customer
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $customer->name) }}"
                                required
                                autofocus
                            />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                id="phone_number"
                                name="phone_number"
                                value="{{ old('phone_number', $customer->phone_number) }}"
                                required
                            />
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control @error('address') is-invalid @enderror"
                                id="address"
                                name="address"
                                rows="3"
                                required
                            >{{ old('address', $customer->address) }}</textarea>
                            @error('address')
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
                                <option value="active" @selected(old('status', $customer->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $customer->status) === 'inactive')>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Customer
                            </button>
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
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
