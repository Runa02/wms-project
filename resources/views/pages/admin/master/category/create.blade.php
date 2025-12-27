@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Add Category</h1>
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
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <!-- Card Body -->
            <div class="p-6">
                <form action="{{ route('category.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Category -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                            Name Category
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            placeholder="Contoh: Bahan Baku"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                            @error('name') border-red-500 @enderror">

                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('category.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 text-sm font-medium text-white
                       bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

            });
        </script>
    @endpush
@endsection
