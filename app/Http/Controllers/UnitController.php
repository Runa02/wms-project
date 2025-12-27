<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('pages.admin.master.unit.index');
    }

    public function getDataUnit()
    {
        $query = Unit::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . route('unit.edit', $row->id) . '"
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
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.admin.master.unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Unit::create([
            'name' => $request->name,
        ]);

        return redirect()->route('unit.index')->with('success', 'Unit Successfully Added');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('pages.admin.master.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        $unit->update($request->all());
        return redirect()->route('unit.index')->with('success', 'Update Success');
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if ($unit) {
            $unit->delete();
            return response()->json(['success' => 'deleted successfully.']);
        } else {
            return response()->json(['error' => 'Unit not found.'], 404);
        }
    }
}
