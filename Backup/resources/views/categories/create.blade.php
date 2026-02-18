@extends('layouts.app')

@section('body_class', 'page-categories')

@section('title', 'Add New Category')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Category Section -->
            <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Add New Category
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf

                        <!-- Category Code -->
                        <div class="mb-3">
                            <label for="code" class="form-label">Category Code</label>
                            <input
                                type="text"
                                class="form-control"
                                id="code"
                                name="code"
                                value="{{ old('code', 'CAT-0001') }}"
                                readonly
                                disabled
                            />
                            <small class="form-text text-muted">This code will be auto-generated upon saving.</small>
                        </div>

                        <!-- Category Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Category
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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
