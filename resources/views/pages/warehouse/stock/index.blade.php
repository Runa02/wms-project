@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Stock Overview</h1>
        <p class="text-sm text-gray-500 mt-1">
            Real-time stock based on Stock In & Stock Out transactions
        </p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white border rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Items</p>
            <h2 class="text-2xl font-semibold text-gray-800 mt-2">
                {{ $totalItems }}
            </h2>
        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Stock In</p>
            <h2 class="text-2xl font-semibold text-green-600 mt-2">
                {{ $totalStockIn }}
            </h2>
        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Stock Out</p>
            <h2 class="text-2xl font-semibold text-red-600 mt-2">
                {{ $totalStockOut }}
            </h2>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white border rounded-xl shadow-sm">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Current Stock</h2>

            <div class="flex gap-2">
                <a href="{{ route('stock-in.index') }}"
                    class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700">
                    Stock In
                </a>
                <a href="{{ route('stock-out.index') }}"
                    class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Stock Out
                </a>
            </div>
        </div>

        <div class="p-6 overflow-x-auto">
            <table id="stockTable" class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Item Code</th>
                        <th class="px-6 py-4">Item Name</th>
                        <th class="px-6 py-4 text-center">Qty Available</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#stockTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock.data') }}",
                pagingType: "simple_numbers",

                // PENGATURAN POSISI
                dom: `
                        <"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4"
                            <"flex items-center gap-2"l>
                            <"flex items-center"f>
                        >
                        rt
                        <"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4"
                            <"text-sm text-gray-600"i>
                            <"flex items-center"p>
                        >
                    `,
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        className: 'text-center font-semibold'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });
        });
    </script>
@endpush
