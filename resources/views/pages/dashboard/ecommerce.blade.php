@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- Total Items -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg hover:scale-[1.02] transition">
            <p class="text-sm opacity-80">Total Items</p>
            <h2 class="text-3xl font-bold mt-3">
                {{ $totalItems }}
            </h2>
            <p class="text-xs opacity-70 mt-1">Registered products</p>
        </div>

        <!-- Stock In -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-6 text-white shadow-lg hover:scale-[1.02] transition">
            <p class="text-sm opacity-80">Total Stock In</p>
            <h2 class="text-3xl font-bold mt-3">
                {{ $totalStockIn }}
            </h2>
            <p class="text-xs opacity-70 mt-1">Incoming stock</p>
        </div>

        <!-- Stock Out -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-6 text-white shadow-lg hover:scale-[1.02] transition">
            <p class="text-sm opacity-80">Total Stock Out</p>
            <h2 class="text-3xl font-bold mt-3">
                {{ $totalStockOut }}
            </h2>
            <p class="text-xs opacity-70 mt-1">Outgoing stock</p>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                Stock In vs Stock Out
            </h3>
            <span class="text-sm text-gray-500">
                Last 6 Months
            </span>
        </div>

        <canvas id="stockChart" height="100"></canvas>
    </div>

    @push('scripts')
        <script>
            const ctx = document.getElementById('stockChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                            label: 'Stock In',
                            data: @json($stockInData),
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                        },
                        {
                            label: 'Stock Out',
                            data: @json($stockOutData),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                        }
                    ]
                }
            });
        </script>
    @endpush
@endsection
