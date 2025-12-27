@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Add Item</h1>

    <!-- Breadcrumb -->
    <div class="breadcrumb mt-3">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Master
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" xmlns="www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <p class="ml-1 text-sm font-medium text-blue-600 md:ml-2">
                            Item
                        </p>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Card -->
    <div class="mt-6">
        <div class="bg-white shadow-md rounded-lg border border-gray-200">
            <div class="p-6">
                <form action="{{ route('items.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Item Code -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Item Code
                            </label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: ITM-001"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                                @error('code') border-red-500 @enderror">
                            @error('code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item Name -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Item Name
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Tepung Terigu"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                                @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Category
                            </label>
                            <select name="category_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                                @error('category_id') border-red-500 @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Unit
                            </label>
                            <select name="unit_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30
                                @error('unit_id') border-red-500 @enderror">
                                <option value="">-- Select Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Minimum -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Minimum Stock
                            </label>
                            <input type="number" name="stock_minimum" value="{{ old('stock_minimum') }}"
                                placeholder="Contoh: 10"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Location
                            </label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                placeholder="Rak A1 / Gudang Utama"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="is_active"
                                class="w-full md:w-1/3 rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                                    Non Active
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('items.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>

                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 text-sm font-medium text-white
                            bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                            Save Item
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
