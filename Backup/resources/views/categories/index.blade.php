@extends('layouts.app')

@section('body_class', 'page-categories')

@section('title', 'Manage Categories')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Category Management</h2>
            <p class="text-muted mb-0">Total Categories: <strong>{{ $categories->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Category
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body">
            @if ($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Category Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr class="text-center">
                                    <td>{{ $category->code }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="{{ $category->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('categories.toggle-status', $category->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this category's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $category->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $category->status === 'active' ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted">No categories found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No categories found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
