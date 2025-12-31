<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockInController extends Controller
{
    public function index()
    {
        return view('pages.warehouse.stock-in.index');
    }

    public function getDataStockIn()
    {
        $query = StockIn::with('item')->get();

        // dd($query);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->date ?? '-';
            })
            ->addColumn('ref', function ($row) {
                return $row->reference_no ?? '-';
            })
            ->addColumn('item', function ($row) {
                return $row->item->name ?? '-';
            })
            ->addColumn('qty', function ($row) {
                return $row->qty ?? '-';
            })
            ->addColumn('created_by', function ($row) {
                return $row->created_by ?? '-';
            })
            ->addColumn('approved_by', function ($row) {
                return $row->approved_by ?? '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'approved') {
                    return '<span class="px-3 py-1 text-xs font-medium rounded-full
                        bg-green-100 text-green-700">
                        Approved
                    </span>';
                } else if ($row->status == 'cancelled') {
                    return '<span class="px-3 py-1 text-xs font-medium rounded-full
                        bg-red-100 text-gray-700">
                        Cancelled
                    </span>';
                }

                return '<span class="px-3 py-1 text-xs font-medium rounded-full
                    bg-gray-100 text-gray-600">
                    Draft
                </span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . route('stock-in.edit', $row->id) . '"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium
                                text-white bg-yellow-500 rounded-md
                                hover:bg-yellow-600 transition">
                            Edit
                        </a>

                        <button
                            type="button"
                            data-id="' . $row->id . '"
                            class="btn-delete inline-flex items-center px-3 py-1.5
                                text-xs font-medium text-white bg-red-500 rounded-md
                                hover:bg-red-600 transition">
                            Delete
                        </button>

                        <button
                            type="button"
                            data-id="' . $row->id . '"
                            class="btn-change-status inline-flex items-center px-3 py-1.5
                                text-xs font-medium text-white bg-blue-500 rounded-md
                                hover:bg-blue-600 transition">
                            Change Status
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $items = Item::all();
        return view('pages.warehouse.stock-in.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id'      => 'required|exists:items,id',
            'qty'          => 'required|integer|min:1',
            'date'         => 'required|date',
            'source'       => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'status'       => 'required|string',
            'note'         => 'nullable|string',
            'created_by'   => 'nullable|string',
            'approved_by'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // 1. Generate reference number
            $referenceNo = $request->reference_no ?? $this->generateStockInRef();

            $stockIn = StockIn::create([
                'item_id'      => $request->item_id,
                'qty'          => $request->qty,
                'date'         => $request->date,
                'source'       => $request->source,
                'reference_no' => $referenceNo,
                'status'       => $request->status,
                'note'         => $request->note,
                'created_by'   => $request->created_by,
                'approved_by'  => $request->approved_by
            ]);

            if ($request->status === 'approved') {
                Stock::updateOrCreate(
                    ['item_id' => $request->item_id],
                    [
                        'qty' => DB::raw('qty + ' . $request->qty)
                    ]
                );
            }
        });

        return redirect()
            ->route('stock-in.index')
            ->with('success', 'Stock In saved');
    }

    public function edit($id)
    {
        $stock = StockIn::findOrFail($id);
        $items = Item::all();
        return view('pages.warehouse.stock-in.edit', compact('items', 'stock'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id'      => 'required|exists:items,id',
            'qty'          => 'required|integer|min:1',
            'date'         => 'required|date',
            'source'       => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'status'       => 'required|string',
            'note'         => 'nullable|string',
            'created_by'   => 'nullable|string',
            'approved_by'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $id) {

            $stockIn = StockIn::lockForUpdate()->findOrFail($id);

            $oldStatus = $stockIn->status;
            $oldQty    = $stockIn->qty;
            $oldItemId = $stockIn->item_id;

            if ($oldStatus === 'approved') {
                Stock::where('item_id', $oldItemId)
                    ->update([
                        'qty' => DB::raw('qty - ' . $oldQty)
                    ]);
            }

            $stockIn->update([
                'item_id'      => $request->item_id,
                'qty'          => $request->qty,
                'date'         => $request->date,
                'source'       => $request->source,
                'reference_no' => $request->reference_no ?? $stockIn->reference_no,
                'status'       => $request->status,
                'note'         => $request->note,
                'created_by'   => $request->created_by,
                'approved_by'  => $request->approved_by,
            ]);

            if ($request->status === 'approved') {
                Stock::updateOrCreate(
                    ['item_id' => $request->item_id],
                    [
                        'qty' => DB::raw('qty + ' . $request->qty)
                    ]
                );
            }
        });

        return redirect()
            ->route('stock-in.index')
            ->with('success', 'Stock In update successfull');
    }

    public function destroy($id)
    {
        $stockIn = StockIn::findOrFail($id);

        if ($stockIn->status !== 'draft') {
            return response()->json([
                'message' => 'Data that has been processed cannot be deleted'
            ], 422);
        }

        $stockIn->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock In deleted successfull'
        ]);
    }

    private function generateStockInRef()
    {
        $date = Carbon::now()->format('Ymd');

        $last = StockIn::whereDate('created_at', Carbon::today())
            ->count() + 1;

        return 'SI-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:stock_ins,id',
            'status' => 'required|in:approved,cancelled',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $stockIn = StockIn::findOrFail($request->id);

                if (
                    $stockIn->status === 'approved'
                    && $request->status === 'approved'
                ) {
                    throw new \Exception('Already approved');
                }

                $stockIn->update([
                    'status' => $request->status,
                ]);

                if ($request->status === 'approved') {
                    Stock::updateOrCreate(
                        ['item_id' => $stockIn->item_id],
                        ['qty' => DB::raw('qty + ' . $stockIn->qty)]
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
