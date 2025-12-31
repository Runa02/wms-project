@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Stock History
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $item->code }} — {{ $item->name }}
        </p>
    </div>

    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Current Stock</p>
            <h2 class="text-2xl font-semibold text-gray-800 mt-2">
                {{ optional($item->stock)->qty ?? 0 }}
            </h2>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Total In</p>
            <h2 class="text-2xl font-semibold text-green-600 mt-2">
                {{ $histories->where('type', 'in')->sum('qty') }}
            </h2>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Total Out</p>
            <h2 class="text-2xl font-semibold text-red-600 mt-2">
                {{ $histories->where('type', 'out')->sum('qty') }}
            </h2>
        </div>
    </div>

    <!-- TIMELINE TABLE -->
    <div class="bg-white border rounded-xl shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Transaction History
            </h2>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3 text-center">Type</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($histories as $row)
                        <tr>
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $row->reference_no ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($row->type === 'in')
                                    <span
                                        class="px-3 py-1 text-xs rounded-full
                                        bg-green-100 text-green-700">
                                        Stock In
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 text-xs rounded-full
                                        bg-red-100 text-red-700">
                                        Stock Out
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center font-semibold">
                                {{ $row->qty }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">
                                No history found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BACK -->
    <div class="mt-6">
        <a href="{{ route('stock.index') }}" class="text-sm text-blue-600 hover:underline">
            ← Back to Stock
        </a>
    </div>
@endsection
