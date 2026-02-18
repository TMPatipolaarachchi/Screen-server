@extends('layouts.app')

@section('body_class', 'page-items')

@section('title', 'Stock - ' . $category->name)

@section('content')
<div class="container-fluid mt-4 mb-5">
    
    <div class="row mb-4">
        <div class="col-md-12">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1" style="color: #1f2937;">{{ $category->name }} Stock</h2>
                    </div>
            </div>
        </div>
    </div>

    <!-- Category Navigation -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2">
                @php
                    $colors = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];
                    $colorIndex = 0;
                @endphp
                @foreach($categories as $cat)
                    @php
                        $color = $colors[$colorIndex % count($colors)];
                        $colorIndex++;
                    @endphp
                    <a href="{{ route('stock.category', $cat->id) }}" 
                       class="category-btn btn btn-{{ $cat->id == $category->id ? $color : 'outline-'.$color }}">
                        <i class="fas fa-layer-group me-2"></i>{{ $cat->name }}
                        <span class="text-light ms-2">{{ $cat->items_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Items with Tab Navigation -->
    <div class="card shadow-sm border-0">
        <div class="card-header border-bottom-0" style="background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%); padding: 1.25rem 1.5rem;">
            <h5 class="mb-0 fw-bold text-dark">{{ $category->name }} Items & Tanks
            </h5>
        </div>
        <div class="card-body p-4">
            @if ($items->count() > 0)
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-3" id="itemsTabs" role="tablist">
                    @foreach($items as $index => $item)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }} border" 
                                    id="item-{{ $item->id }}-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#item-{{ $item->id }}" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-box me-1 text-dark"></i><span class="text-dark"> {{ $item->name }}</span>
                                <span class="ms-2 text-dark">{{ number_format($item->stock_quantity, 2) }} L</span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="itemsTabsContent">
                    @foreach($items as $index => $item)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                             id="item-{{ $item->id }}" 
                             role="tabpanel">
                            <!-- Item Details Card -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="item-details-card">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-2 text-center">
                                                    <div class="item-icon-wrapper-pro">
                                                        <i class="fas fa-box-open fa-3x"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="item-info-section">
                                                        <h3 class="item-title mb-2">{{ $item->name }}</h3>
                                                        <div class="item-meta">
                                                            <div class="meta-item">
                                                                <span class="meta-label">Code:</span>
                                                                <span class="meta-value">{{ $item->item_code }}</span>
                                                            </div>
                                                            <div class="meta-divider"></div>
                                                            <div class="meta-item">
                                                                <span class="meta-label">Total Stock:</span>
                                                                <span class="meta-value fw-bold text-primary">{{ number_format($item->stock_quantity, 2) }} L</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <a href="{{ route('stock.logs', $item->id) }}" class="btn btn-primary btn-lg px-4 py-3 history-btn">
                                                        <i class="fas fa-history me-2"></i> View History
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tank Distribution -->
                            <div class="row">
                                @if($item->tanks->count() > 0)
                                    @foreach($item->tanks as $tank)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="tank-card h-100">
                                                <div class="tank-card-header mb-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="tank-name-badge">
                                                            <i class="fas fa-database me-2"></i>{{ $tank->tank_name }}
                                                        </span>
                                                        @php
                                                            $percentage = $tank->max_stock > 0 ? ($tank->current_stock / $tank->max_stock) * 100 : 0;
                                                            $statusClass = $percentage > 75 ? 'success' : ($percentage > 25 ? 'warning' : 'danger');
                                                            $statusIcon = $percentage > 75 ? 'check-circle' : ($percentage > 25 ? 'exclamation-triangle' : 'exclamation-circle');
                                                        @endphp
                                                        <span class="status-badge badge-{{ $statusClass }}">
                                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>{{ number_format($percentage, 0) }}%
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="tank-body">
                                                    <div class="stock-display mb-3">
                                                        <div class="current-stock">
                                                            <span class="stock-label">Current Stock</span>
                                                            <span class="stock-value">{{ number_format($tank->current_stock, 2) }}<small>L</small></span>
                                                        </div>
                                                        <div class="divider-line"></div>
                                                        <div class="max-stock">
                                                            <span class="stock-label">Capacity</span>
                                                            <span class="stock-value text-muted">{{ number_format($tank->max_stock, 2) }}<small>L</small></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="progress-wrapper mb-3">
                                                        <div class="progress tank-progress">
                                                            <div class="progress-bar progress-{{ $statusClass }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ min($percentage, 100) }}%"
                                                                 aria-valuenow="{{ $tank->current_stock }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="{{ $tank->max_stock }}">
                                                                <span class="progress-label">{{ number_format($percentage, 1) }}%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="available-space-card">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted small">
                                                                <i class="fas fa-arrow-down me-1"></i>Available Space
                                                            </span>
                                                            <strong class="text-success fs-5">
                                                                {{ number_format($tank->max_stock - $tank->current_stock, 2) }} L
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>No tanks assigned</strong> to this item yet.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> No items found in this category.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .header-icon-wrapper {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 6px rgba(245, 158, 11, 0.3);
    }

    /* Category Navigation */
    .category-btn {
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        border-width: 2px;
    }

    .category-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .category-btn .badge {
        font-weight: 600;
    }

    /* Tab Navigation Styling */
    .nav-tabs {
        border-bottom: 2px solid #e5e7eb;
        background: #f9fafb;
        padding: 0.5rem 0.5rem 0;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs .nav-link {
        color: #6b7280;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        padding: 0.875rem 1.5rem;
        transition: all 0.3s ease;
        border-radius: 8px 8px 0 0;
        margin: 0 0.25rem;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(245, 158, 11, 0.1);
        border-color: #f59e0b;
        color: #f59e0b;
    }

    .nav-tabs .nav-link.active {
        color: #f59e0b;
        background-color: #ffffff;
        border-color: #f59e0b;
        font-weight: 700;
        box-shadow: 0 -2px 8px rgba(245, 158, 11, 0.15);
    }

    .nav-tabs .nav-link .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        font-weight: 600;
    }

    /* Item Details Card */
    .item-details-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 2px solid #e5e7eb;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
    }

    .item-details-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .item-details-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
    }

    .item-icon-wrapper-pro {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .item-icon-wrapper-pro:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 12px 28px rgba(59, 130, 246, 0.3);
    }

    .item-info-section {
        padding-left: 1rem;
        border-left: 3px solid #e5e7eb;
    }

    .item-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1f2937;
        letter-spacing: -0.5px;
        margin-bottom: 1rem;
    }

    .item-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f9fafb;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .meta-item:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .meta-item i {
        color: #6b7280;
        font-size: 1.1rem;
    }

    .meta-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }

    .meta-value {
        font-size: 0.95rem;
        color: #1f2937;
        font-weight: 700;
    }

    .meta-divider {
        width: 2px;
        height: 30px;
        background: linear-gradient(180deg, transparent 0%, #d1d5db 50%, transparent 100%);
    }

    .history-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }

    .history-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    /* Tank Card Styling */
    .tank-card {
        background: #ffffff;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .tank-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .tank-card:hover {
        border-color: #f59e0b;
        box-shadow: 0 12px 24px rgba(245, 158, 11, 0.2);
        transform: translateY(-8px);
    }

    .tank-card:hover::before {
        transform: scaleX(1);
    }

    .tank-card-header {
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .tank-name-badge {
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
        display: inline-flex;
        align-items: center;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .status-badge.badge-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .status-badge.badge-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .status-badge.badge-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .tank-body {
        padding-top: 1rem;
    }

    .stock-display {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.25rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-around;
    }

    .current-stock, .max-stock {
        text-align: center;
        flex: 1;
    }

    .stock-label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stock-value {
        display: block;
        font-size: 1.75rem;
        font-weight: 800;
        color: #1f2937;
        line-height: 1;
    }

    .stock-value small {
        font-size: 1rem;
        font-weight: 600;
        color: #9ca3af;
        margin-left: 0.25rem;
    }

    .divider-line {
        width: 2px;
        height: 50px;
        background: linear-gradient(180deg, transparent 0%, #d1d5db 50%, transparent 100%);
    }

    .progress-wrapper {
        position: relative;
    }

    .tank-progress {
        height: 32px;
        border-radius: 16px;
        background: #e5e7eb;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .tank-progress .progress-bar {
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .tank-progress .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            90deg,
            transparent 0%,
            rgba(255, 255, 255, 0.2) 50%,
            transparent 100%
        );
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-label {
        position: relative;
        z-index: 1;
    }

    .progress-success {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
    }

    .progress-warning {
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
    }

    .progress-danger {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }

    .available-space-card {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        padding: 1rem;
        border-radius: 10px;
        border: 2px dashed #10b981;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tank-card {
            margin-bottom: 1rem;
        }

        .stock-display {
            flex-direction: column;
            gap: 1rem;
        }

        .divider-line {
            width: 100%;
            height: 2px;
        }

        .nav-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .item-details-card .card-body {
            padding: 1rem;
        }

        .header-icon-wrapper {
            width: 50px;
            height: 50px;
        }
    }

    /* Alert Enhancements */
    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    /* Smooth Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-pane {
        animation: fadeIn 0.5s ease-out;
    }

    /* Button Enhancements */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
