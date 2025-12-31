@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Stock Out</h1>
    <div class="breadcrumb mt-3">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Warehouse
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="www.w3.org" fill="none"
                            viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ml-1 text-sm font-medium text-blue-600 md:ml-2">
                            Stock Out
                        </p>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mt-6">
        <div class="flex justify-end py-4">
            <a href="{{ route('stock-out.create') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                + Add Stock Out
            </a>
        </div>
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <!-- Card Body -->
            <div class="p-6 overflow-x-auto">
                <table id="itemTable" class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Reference Number</th>
                            <th class="px-6 py-4">Item</th>
                            <th class="px-6 py-4">Qty</th>
                            <th class="px-6 py-4">Created By</th>
                            <th class="px-6 py-4">Approved By</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="w-50 text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-md p-6">

            <h3 class="text-lg font-semibold mb-4">Change Status</h3>

            <form id="statusForm">
                @csrf
                <input type="hidden" name="id" id="stockInId">

                <label class="block text-sm font-medium mb-2">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 mb-4">
                    <option value="approved">Approved</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let table;
            $(document).ready(function() {
                table = $('#itemTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('stock-out.data') }}",
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
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'ref',
                            name: 'ref'
                        },
                        {
                            data: 'item',
                            name: 'item'
                        },
                        {
                            data: 'qty',
                            name: 'qty'
                        },
                        {
                            data: 'created_by',
                            name: 'created_by'
                        },
                        {
                            data: 'approved_by',
                            name: 'approved_by'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $(document).on('click', '.btn-change-status', function() {
                    $('#stockInId').val($(this).data('id'));
                    $('#statusModal').removeClass('hidden').addClass('flex');
                });

                $('#closeModal').on('click', function() {
                    $('#statusModal').addClass('hidden').removeClass('flex');
                });

                $('#statusForm').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('stock-in.change-status') }}",
                        method: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            $('#statusModal').addClass('hidden').removeClass('flex');
                            table.ajax.reload(null, false);

                            toastr.success(res.message);
                        },
                        error: function(xhr) {

                            let message = 'Terjadi kesalahan';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            toastr.error(message);
                        }
                    });
                });

                $('body').on('click', '.btn-delete', function() {
                    const id = $(this).data('id');

                    Swal.fire({
                        title: 'Delete Stock In?',
                        text: 'Only data with DRAFT status may be deleted.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('stock-in.destroy', ':id') }}".replace(':id',
                                    id),
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    _method: 'DELETE'
                                },
                                success: function(res) {
                                    toastr.success(res.message ??
                                        'Stock In delete success');
                                    table.ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    let message = 'Failed to delete data ';

                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }

                                    toastr.error(message);
                                }
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
