@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Edit User')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Edit User: {{ $user->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
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
                                value="{{ old('name', $user->name) }}"
                                required
                            />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                            />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="text"
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                            />
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIC Number -->
                        <div class="mb-3">
                            <label for="nic_number" class="form-label">NIC Number</label>
                            <input
                                type="text"
                                class="form-control @error('nic_number') is-invalid @enderror"
                                id="nic_number"
                                name="nic_number"
                                value="{{ old('nic_number', $user->nic_number) }}"
                            />
                            @error('nic_number')
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
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrator</option>
                                <option value="user1" @selected(old('role', $user->role) === 'user1')>User 1</option>
                                <option value="user2" @selected(old('role', $user->role) === 'user2')>User 2</option>
                                <option value="user3" @selected(old('role', $user->role) === 'user3')>User 3</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- User Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required
                            >
                                <option value="">-- Select Status --</option>
                                <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Info -->
                        <div class="mb-3">
                            <label class="form-label">Additional Information</label>
                            <div class="form-control-plaintext">
                                <small class="d-block">
                                    <strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}
                                </small>
                                <small class="d-block">
                                    <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
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
