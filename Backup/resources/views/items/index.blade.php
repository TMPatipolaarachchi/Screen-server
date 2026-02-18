@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Manage Items')

@section('content')
<div class="container-fluid mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Item Management</h2>
            <p class="text-muted mb-0">Total Items: <strong>{{ $items->count() }}</strong></p>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New Item
            </a>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card">
        <div class="card-body">
            @if ($items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="text-center">
                                <th>Item Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock Quantity</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>VAT</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr class="text-center">
                                    <td>{{ $item->item_code }}</td>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                    </td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ number_format($item->stock_quantity, 0) }} L
                                        </span>
                                    </td>
                                    <td>LKR {{ $item->cost_price }}</td>
                                    <td>LKR {{ number_format($item->selling_price, 2) }}</td>
                                    <td>
                                        <span class="{{ $item->vat_available ? ' text-primary' : ' text-secondary' }}">
                                            {{ $item->vat_available ? 'Available' : 'Not Available' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $item->status === 'active' ? 'text-success' : 'text-warning' }} fw-bold">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('items.toggle-status', $item->id) }}" class="d-inline" data-confirm-message="Are you sure you want to change this item's status?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $item->status === 'active' ? 'danger' : 'success' }}">
                                                <i class="fas fa-{{ $item->status === 'active' ? 'times' : 'check' }}" style="color: black;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <p class="text-muted">No items found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No items found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
