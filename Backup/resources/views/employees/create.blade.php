@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Create Employee')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Employee Section -->
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Add New Employee
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
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

                        <!-- NIC -->
                        <div class="mb-3">
                            <label for="nic" class="form-label">NIC <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('nic') is-invalid @enderror"
                                id="nic"
                                name="nic"
                                value="{{ old('nic') }}"
                                required
                            />
                            @error('nic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ETF Number -->
                        <div class="mb-3">
                            <label for="etf_number" class="form-label">ETF Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('etf_number') is-invalid @enderror"
                                id="etf_number"
                                name="etf_number"
                                value="{{ old('etf_number') }}"
                                required
                            />
                            @error('etf_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Birthday -->
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                class="form-control @error('birthday') is-invalid @enderror"
                                id="birthday"
                                name="birthday"
                                value="{{ old('birthday') }}"
                                max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                required
                            />
                            @error('birthday')
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
                            >{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required
                            >
                                <option value="">-- Select Role --</option>
                                <option value="pumper" @selected(old('role') === 'pumper')>Pumper</option>
                                <option value="driver" @selected(old('role') === 'driver')>Driver</option>
                                <option value="helper" @selected(old('role') === 'helper')>Helper</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Employee
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
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
