@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Category</h1>
    <div class="breadcrumb mt-3">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Master
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
                            Category
                        </p>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mt-6">
        <div class="flex justify-end py-4">
            <a href="{{ route('category.create') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                + Add Category
            </a>
        </div>
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <!-- Card Body -->
            <div class="p-6 overflow-x-auto">
                <table id="categoryTable" class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                        <tr>
                            <th class="w-50 px-6 py-4">No</th>
                            <th class="px-6 py-4">Name Category</th>
                            <th class="w-50 text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let table;
            $(document).ready(function() {
                table = $('#categoryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('category.data') }}",
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
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('body').on('click', '.btn-delete', function() {
                    const id = $(this).data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This data will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#155DFC',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('category.destroy', ':id') }}".replace(':id',
                                    id),
                                type: 'POST',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    _method: 'DELETE'
                                },
                                success: function() {
                                    Swal.fire(
                                        'Deleted!',
                                        'Category has been deleted.',
                                        'success'
                                    );
                                    table.ajax.reload(null, false);
                                },
                                error: function() {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete data.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
