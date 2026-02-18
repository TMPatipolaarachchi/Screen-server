@extends('layouts.app')

@section('body_class', 'page-categories')

@section('title', 'Category Details')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                       Category Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Category Name:</strong><br>
                                {{ $category->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <span class="{{ $category->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Category Code:</strong><br>
                                {{ $category->code }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Created:</strong><br>
                                {{ $category->created_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $category->updated_at->format('M d, Y - h:i A') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
