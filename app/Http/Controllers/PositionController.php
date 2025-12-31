<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    public function index()
    {
        return view('pages.admin.master.position.index');
    }

    public function getDataPosition()
    {
        $query = Position::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex items-center justify-center gap-2">
                        <a href="' . route('position.edit', $row->id) . '"
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
        $permissions = Permission::all();
        return view('pages.admin.master.position.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:positions,name',
            'permissions' => 'nullable|array',
        ]);

        $position = Position::create([
            'name' => $request->name,
        ]);

        $roleName = str()->slug($request->name, '_');

        $role = Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('position.index')
            ->with('success', 'Position & Role successfully added');
    }

    public function edit($id)
    {
        $position = Position::findOrFail($id);

        $roleName = str()->slug($position->name, '_');
        $role = Role::where('name', $roleName)->first();

        $permissions = Permission::all();
        $rolePermissions = $role
            ? $role->permissions->pluck('name')->toArray()
            : [];

        return view(
            'pages.admin.master.position.edit',
            compact('position', 'permissions', 'rolePermissions')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $position = Position::findOrFail($id);

        $roleName = str()->slug($position->name, '_');
        $role = Role::where('name', $roleName)->first();

        if ($role && $request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('position.index')
            ->with('success', 'Position permissions updated');
    }

    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json(['error' => 'Position not found.'], 404);
        }

        $roleName = str()->slug($position->name, '_');
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $role->delete();
        }

        $position->delete();

        return response()->json(['success' => 'Deleted successfully']);
    }
}
