@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold">Edit Stock In</h1>

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
                            Stock In
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
                <form action="{{ route('stock-in.update', $stock->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Item
                            </label>
                            <select name="item_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                                <option value="">-- Select Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('item_id', $stock->item_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->code }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Qty -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Quantity
                            </label>
                            <input type="number" name="qty" min="1" value="{{ old('qty', $stock->qty) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Date
                            </label>
                            <input type="text" id="date" name="date" value="{{ old('date', $stock->date) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        </div>

                        <!-- Source -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Source
                            </label>
                            <select name="source" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                                <option value="purchase"
                                    {{ old('source', $stock->source) == 'purchase' ? 'selected' : '' }}>
                                    Purchase</option>
                                <option value="return" {{ old('source', $stock->source) == 'return' ? 'selected' : '' }}>
                                    Return</option>
                                <option value="adjustment"
                                    {{ old('source', $stock->source) == 'adjustment' ? 'selected' : '' }}>Adjustment
                                </option>
                                <option value="production"
                                    {{ old('source', $stock->source) == 'production' ? 'selected' : '' }}>Production
                                </option>
                            </select>
                        </div>

                        <!-- Reference -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Reference No
                            </label>
                            <input type="text" name="reference_no"
                                value="{{ old('reference_no', $stock->reference_no) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                                <option value="approved"
                                    {{ old('status', $stock->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="draft" {{ old('status', $stock->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Created By
                            </label>
                            <input type="text" name="created_by" value="{{ old('created_by', $stock->created_by) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Approved by
                            </label>
                            <input type="text" name="approved_by" value="{{ old('approved_by', $stock->approved_by) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                        </div>

                        <!-- Note -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Note
                            </label>
                            <textarea name="note" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('note', $stock->note) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('stock-in.index') }}"
                            class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr("#date", {
                    dateFormat: "Y-m-d",
                    defaultDate: "{{ old('date', now()->toDateString()) }}"
                });
            });
        </script>
    @endpush
@endsection
