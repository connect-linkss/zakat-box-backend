@extends('layouts.admin.app')

@section('title', translate('monthlyearning Report'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <form method="GET" action="{{ route('admin.report.expence') }}">
                <div class="d-flex gap-2">
                    <div class="form-group">
                        <label class="input-label">Start Date<span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="start_date"
                            name="from"
                            class="form-control"
                            value="{{ request()->get('from', now()->format('Y-m-d')) }}"
                            max="{{ now()->addDay()->format('Y-m-d') }}"
                            onchange="adjustEndDate()"
                        />
                    </div>
                    <div class="form-group">
                        <label class="input-label">End Date<span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="end_date"
                            name="to"
                            class="form-control"
                            value="{{ request()->get('to', now()->addDay()->format('Y-m-d')) }}"
                            max="{{ now()->addDay()->format('Y-m-d') }}"
                        />
                    </div>
                    <div class="d-flex gap-2">
                        <div class="form-group">
                            <label class="input-label">Category</label>
                            <select name="category_id" class="form-control" id="category">
                                <option value="">All Categories</option>
                                @foreach($categoriesParent as $category)
                                    <option value="{{ $category->id }}" {{ request()->get('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="input-label">Subcategory</label>
                            <select name="subcategory_id" class="form-control" id="subcategory">
                                <option value="">All Subcategories</option>
                                @foreach($categoriessub as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ request()->get('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" style="visibility: hidden;">test</label>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="d-flex align-items-center gap-5 sales-data">
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">Total Expences</label>
                    <span style="color: #3347E6FF">{{ number_format($total, 2) }}$</span>
                </div>
            </div>
        </div>

        </div>
        <div class="table-responsive datatable-custom monthly">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Subcategory') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Description') }}</th>
                            <th>{{ translate('Expense Total') }}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @php
                            $total = 0;
                            $orderCount = 0;
                        @endphp

                        @foreach($orders as $order)
                            @php
                                $total += $order->total;
                                $orderCount++;
                            @endphp
                            <tr>
                                <td>{{ $order->category->name ?? 'N/A' }}</td>
                                <td>{{ $order->subcategory->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>{{ $order->title ?? 'N/A' }}</td>
                                <td>{{ number_format($order->amount, 2) }}$</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
    </div>

@endsection
@push('script_2')
<script>
    document.getElementById('category').addEventListener('change', function () {
        const categoryId = this.value;
        const subcategorySelect = document.getElementById('subcategory');

        // Clear previous options
        subcategorySelect.innerHTML = '<option value="">All Subcategories</option>';

        // Fetch subcategories for the selected category
        @php
            $subcategoriesByCategory = $categoriessub->groupBy('parent_id');
        @endphp

        const subcategories = @json($subcategoriesByCategory);
        if (subcategories[categoryId]) {
            subcategories[categoryId].forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory.id;
                option.textContent = subcategory.name;
                subcategorySelect.appendChild(option);
            });
        }
    });
</script>
<script>
    function adjustEndDate() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startDate = new Date(startDateInput.value);
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        // Set max and min constraints for End Date
        endDateInput.max = tomorrow.toISOString().split('T')[0]; // Tomorrow's date
        const minEndDate = new Date(startDate);
        minEndDate.setDate(startDate.getDate() + 1); // One day after Start Date
        endDateInput.min = minEndDate.toISOString().split('T')[0];
        // Adjust End Date if it's invalid
        const endDate = new Date(endDateInput.value);
        if (endDate < minEndDate || endDate > tomorrow) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        }
    }
    // Initialize constraints on page load
    document.addEventListener('DOMContentLoaded', adjustEndDate);
</script>
@endpush

