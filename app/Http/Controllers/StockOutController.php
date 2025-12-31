<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockOutController extends Controller
{
    public function index()
    {
        return view('pages.warehouse.stock-out.index');
    }

    public function getDataStockOut()
    {
        $query = StockOut::with('item')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('date', fn($row) => $row->date ?? '-')
            ->addColumn('ref', fn($row) => $row->reference_no ?? '-')
            ->addColumn('item', fn($row) => $row->item->name ?? '-')
            ->addColumn('qty', fn($row) => $row->qty ?? '-')
            ->addColumn('created_by', fn($row) => $row->created_by ?? '-')
            ->addColumn('approved_by', fn($row) => $row->approved_by ?? '-')
            ->addColumn('status', function ($row) {
                if ($row->status === 'approved') {
                    return '<span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">Approved</span>';
                } elseif ($row->status === 'cancelled') {
                    return '<span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">Cancelled</span>';
                }
                return '<span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Draft</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex gap-2 justify-center">
                        <a href="' . route('stock-out.edit', $row->id) . '" class="px-3 py-1 text-xs bg-yellow-500 text-white rounded">Edit</a>
                        <button data-id="' . $row->id . '" class="btn-delete px-3 py-1 text-xs bg-red-500 text-white rounded">Delete</button>
                        <button data-id="' . $row->id . '" class="btn-change-status px-3 py-1 text-xs bg-blue-500 text-white rounded">Change Status</button>
                    </div>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $items = Item::with('stock')->get();
        return view('pages.warehouse.stock-out.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id'      => 'required|exists:items,id',
            'qty'          => 'required|integer|min:1',
            'date'         => 'required|date',
            'source'       => 'nullable|in:sales,usage,damaged, lost, adjustment',
            'reference_no' => 'nullable|string|max:100',
            'status'       => 'required|in:draft,approved,cancelled',
            'note'         => 'nullable|string',
            'created_by'   => 'nullable|string',
            'approved_by'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $stock = Stock::where('item_id', $request->item_id)->lockForUpdate()->first();

            if (
                $request->status === 'approved'
                && (!$stock || $stock->qty < $request->qty)
            ) {
                throw new \Exception('Stock tidak mencukupi');
            }

            $stockOut = StockOut::create([
                'item_id'      => $request->item_id,
                'qty'          => $request->qty,
                'date'         => $request->date,
                'source'       => $request->source,
                'reference_no' => $request->reference_no ?? $this->generateStockOutRef(),
                'status'       => $request->status,
                'note'         => $request->note,
                'created_by'   => $request->created_by,
                'approved_by'  => $request->approved_by,
            ]);

            if ($request->status === 'approved') {
                $stock->decrement('qty', $request->qty);
            }
        });

        return redirect()->route('stock-out.index')
            ->with('success', 'Stock Out saved');
    }

    public function destroy($id)
    {
        $stockOut = StockOut::findOrFail($id);

        if ($stockOut->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft data can be deleted'
            ], 422);
        }

        $stockOut->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock Out deleted'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:stock_outs,id',
            'status' => 'required|in:approved,cancelled',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $stockOut = StockOut::lockForUpdate()->findOrFail($request->id);
                $stock    = Stock::where('item_id', $stockOut->item_id)->lockForUpdate()->first();

                if ($request->status === 'approved') {
                    if (!$stock || $stock->qty < $stockOut->qty) {
                        throw new \Exception('Stock tidak mencukupi');
                    }
                    $stock->decrement('qty', $stockOut->qty);
                }

                $stockOut->update(['status' => $request->status]);
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function generateStockOutRef()
    {
        $date = Carbon::now()->format('Ymd');
        $count = StockOut::whereDate('created_at', today())->count() + 1;

        return 'SO-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
