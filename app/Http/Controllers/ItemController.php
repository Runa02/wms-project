<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        return view('pages.admin.master.item.index');
    }

    public function getDataItems()
    {
        $query = Item::with('unit', 'category')->get();

        // dd($query);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('code', function ($row) {
                return $row->code ?? '-';
            })
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('unit', function ($row) {
                return $row->unit->name ?? '-';
            })
            ->addColumn('category', function ($row) {
                return $row->category->name ?? '-';
            })
            ->addColumn('location', function ($row) {
                return $row->location ?? '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->is_active == 1) {
                    return '<span class="px-3 py-1 text-xs font-medium rounded-full
                        bg-green-100 text-green-700">
                        Active
                    </span>';
                }

                return '<span class="px-3 py-1 text-xs font-medium rounded-full
                    bg-red-100 text-gray-600">
                    Non Active
                </span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . route('items.edit', $row->id) . '"
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
                    </div>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('pages.admin.master.item.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'           => 'nullable|string|max:50|unique:items,code',
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'unit_id'        => 'nullable|exists:units,id',
            'stock_minimum'  => 'nullable|integer|min:0',
            'location'       => 'nullable|string|max:100',
            'is_active'      => 'required|boolean',
        ], [
            'name.required' => 'Item name wajib diisi',
        ]);

        // for random code
        $code = $request->code ?? 'ITM-' . str_pad(Item::count() + 1, 4, '0', STR_PAD_LEFT);

        Item::create([
            'code'          => $code,
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'unit_id'       => $request->unit_id,
            'stock_minimum' => $request->stock_minimum ?? 0,
            'location'      => $request->location,
            'is_active'     => $request->is_active,
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $units = Unit::all();
        $categories = Category::all();
        return view('pages.admin.master.item.edit', compact('units', 'categories', 'item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('items', 'code')->ignore($item->id),
            ],
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'unit_id'        => 'nullable|exists:units,id',
            'stock_minimum'  => 'nullable|integer|min:0',
            'location'       => 'nullable|string|max:100',
            'is_active'      => 'required|boolean',
        ]);

        $data = [
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'unit_id'        => $request->unit_id,
            'stock_minimum'  => $request->stock_minimum ?? 0,
            'location'       => $request->location,
            'is_active'      => $request->is_active,
        ];

        if ($request->filled('code') && $request->code !== $item->code) {
            $data['code'] = $request->code;
        }

        $item->update($data);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        if ($item) {
            $item->delete();
            return response()->json(['success' => 'deleted successfully.']);
        } else {
            return response()->json(['error' => 'Item not found.'], 404);
        }
    }
}
