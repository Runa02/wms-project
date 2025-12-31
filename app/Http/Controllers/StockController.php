<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    public function index()
    {
        // Stock sekarang
        $stocks = Stock::with('item')->get();

        // Summary
        $totalItems = $stocks->count();
        $totalStockIn = StockIn::where('status', 'approved')->sum('qty');
        $totalStockOut = StockOut::where('status', 'approved')->sum('qty');

        return view('pages.warehouse.stock.index', compact(
            'totalItems',
            'totalStockIn',
            'totalStockOut'
        ));
    }

    public function data()
    {
        $query = Stock::with('item');

        return DataTables::of($query)
            ->addColumn('code', function ($row) {
                return $row->item->code ?? '-';
            })
            ->addColumn('name', function ($row) {
                return $row->item->name ?? '-';
            })
            ->addColumn('qty', function ($row) {
                if ($row->qty <= 5) {
                    return '<span class="text-red-600 font-bold">' . $row->qty . '</span>';
                }

                return '<span class="text-green-600 font-semibold">' . $row->qty . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('stock.history', $row->item_id) . '"
                    class="inline-flex items-center px-3 py-1.5
                            text-xs font-medium text-blue-600
                            bg-blue-50 rounded-lg hover:bg-blue-100">
                        History
                    </a>
                ';
            })
            ->rawColumns(['qty', 'action'])
            ->make(true);
    }

    public function history(Item $item)
    {
        $stockIns = StockIn::where('item_id', $item->id)
            ->where('status', 'approved')
            ->select(
                'date',
                'reference_no',
                'qty',
                DB::raw("'in' as type"),
                'created_at'
            );

        $stockOuts = StockOut::where('item_id', $item->id)
            ->where('status', 'approved')
            ->select(
                'date',
                'reference_no',
                'qty',
                DB::raw("'out' as type"),
                'created_at'
            );

        $histories = $stockIns
            ->unionAll($stockOuts)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.warehouse.stock.history', compact(
            'item',
            'histories'
        ));
    }
}
