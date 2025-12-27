<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.admin.master.category.index');
    }

    public function getDataCategory()
    {
        $query = Category::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . route('category.edit', $row->id) . '"
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
        return view('pages.admin.master.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('category.index')->with('success', 'Category Successfully Added');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.admin.master.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->all());
        return redirect()->route('category.index')->with('success', 'Update Success');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['success' => 'deleted successfully.']);
        } else {
            return response()->json(['error' => 'Category not found.'], 404);
        }
    }
}
