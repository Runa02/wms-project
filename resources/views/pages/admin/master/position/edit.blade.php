@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Edit Position</h1>

    {{-- Breadcrumb --}}
    <div class="breadcrumb mt-3">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li>
                    <a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Master
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" xmlns="http://www.w3.org" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="text-sm font-medium text-blue-600">
                        Position
                    </span>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Card --}}
    <div class="mt-6 bg-white shadow-md rounded-lg border border-gray-200">
        <div class="p-6">
            <form action="{{ route('position.update', $position->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Position Name (Read Only) --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Position Name
                    </label>

                    <input type="text" value="{{ $position->name }}" disabled
                        class="w-full rounded-lg border bg-gray-100 px-4 py-2.5 text-sm text-gray-600">

                    <p class="mt-1 text-xs text-gray-500">
                        Position name cannot be changed because it is linked to system role.
                    </p>
                </div>

                {{-- Permissions --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">
                            Permissions
                        </h3>

                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" id="checkAll"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Select All
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($permissions as $permission)
                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer
                                hover:bg-gray-50 transition">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>

                                <span class="text-sm text-gray-700 capitalize">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Action --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('position.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100
                        rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600
                        rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('#checkAll').on('change', function() {
                    $('input[name="permissions[]"]').prop('checked', this.checked);
                });
            });
        </script>
    @endpush
@endsection
