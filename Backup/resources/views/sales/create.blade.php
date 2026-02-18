@extends('layouts.app')

@section('body_class', 'page-users')

@section('title', 'Create Sale')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Add Sale Section -->
            <div class="card">
                <div class="card-header bg-light border-bottom">
                    <h4 class="mb-0 fw-bold">
                        Create New Sale Record
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sales.store') }}">
                        @csrf

                        <!-- Meter Name -->
                        <div class="mb-3">
                            <label for="meter_id" class="form-label">Meter Name <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('meter_id') is-invalid @enderror"
                                id="meter_id"
                                name="meter_id"
                                required
                                autofocus
                            >
                                <option value="">-- Select Meter --</option>
                                @foreach($meters as $meter)
                                    <option value="{{ $meter->id }}" @selected(old('meter_id') == $meter->id)>
                                        {{ $meter->meter_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('meter_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pump Name -->
                        <div class="mb-3">
                            <label for="pump_id" class="form-label">Pump Name <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('pump_id') is-invalid @enderror"
                                id="pump_id"
                                name="pump_id"
                                required
                            >
                                <option value="">-- Select Pump --</option>
                                @foreach($pumps as $pump)
                                    <option value="{{ $pump->id }}" @selected(old('pump_id') == $pump->id)>
                                        {{ $pump->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pump_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name of Pumper -->
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Name of Pumper <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('employee_id') is-invalid @enderror"
                                id="employee_id"
                                name="employee_id"
                                required
                            >
                                <option value="">-- Select Pumper --</option>
                                @foreach($pumpers as $pumper)
                                    <option value="{{ $pumper->id }}" @selected(old('employee_id') == $pumper->id)>
                                        {{ $pumper->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Meter Value -->
                        <div class="mb-3">
                            <label for="last_meter_value" class="form-label">Last Meter Value <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('last_meter_value') is-invalid @enderror"
                                id="last_meter_value"
                                name="last_meter_value"
                                value="{{ old('last_meter_value') }}"
                                required
                            />
                            @error('last_meter_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Create Sale Record
                            </button>
                            <a href="{{ route('sales.status') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const meterSelect = document.getElementById('meter_id');
    const lastMeterValueInput = document.getElementById('last_meter_value');
    const helpText = document.getElementById('meter_value_help');

    meterSelect.addEventListener('change', function() {
        const meterId = this.value;
        
        if (!meterId) {
            lastMeterValueInput.value = '';
            lastMeterValueInput.readOnly = false;
            helpText.textContent = 'Enter the current meter reading.';
            helpText.className = 'text-muted';
            return;
        }

        // Fetch the last meter value for this meter
        fetch(`/admin/sales/meter/${meterId}/last-value`)
            .then(response => response.json())
            .then(data => {
                if (data.has_previous) {
                    lastMeterValueInput.value = data.last_value;
                    lastMeterValueInput.readOnly = true;
                    helpText.textContent = `Auto-populated from meter's current value. This value will be the starting point for this sale.`;
                    helpText.className = 'text-success fw-bold';
                } else {
                    lastMeterValueInput.value = '';
                    lastMeterValueInput.readOnly = false;
                    helpText.textContent = 'No meter value found. Enter the meter reading manually.';
                    helpText.className = 'text-info';
                }
            })
            .catch(error => {
                console.error('Error fetching last meter value:', error);
                lastMeterValueInput.readOnly = false;
                helpText.textContent = 'Enter the current meter reading.';
                helpText.className = 'text-muted';
            });
    });
});
</script>
@endsection
