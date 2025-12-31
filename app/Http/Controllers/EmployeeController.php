<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('pages.admin.master.employee.index');
    }

    public function getDataEmployee()
    {
        $query = Employee::with('position')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('position', function ($row) {
                return $row->position->name ?? '-';
            })
            ->addColumn('join_date', function ($row) {
                return $row->join_date ?? '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'active') {
                    return '<span class="px-3 py-1 text-xs font-medium rounded-full
                        bg-green-100 text-green-700">
                        Active
                    </span>';
                }

                return '<span class="px-3 py-1 text-xs font-medium rounded-full
                    bg-red-300 text-gray-600">
                    Resign
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
        $position = Position::all();
        return view('pages.admin.master.employees.create', compact('position'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_id'    => 'nullable|exists:positions,id',
            'name'           => 'required|string|max:255',
            'join_date'      => 'nullable|date',
            'status'         => 'nullable|string',
        ]);

        Employee::create([
            'position_id'   => $request->position_id,
            'name'          => $request->name,
            'join_date'     => $request->join_date,
            'status'       => $request->status,
        ]);

        return redirect()
            ->route('employee.index')
            ->with('success', 'Item berhasil ditambahkan');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $position = Position::all();
        return view('pages.admin.master.employee.edit', compact('position', 'employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'position_id'    => 'nullable|exists:positions,id',
            'name'           => 'required|string|max:255',
            'join_date'      => 'nullable|date',
            'status'         => 'nullable|string',
        ]);

        $data = [
            'position_id'    => $request->position_id,
            'name'           => $request->name,
            'join_date'      => $request->join_date,
            'status'         => $request->status,
        ];

        $employee->update($data);

        return redirect()->route('employee.index')
            ->with('success', 'Employee Successful Updated');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee) {
            $employee->delete();
            return response()->json(['success' => 'deleted successfully.']);
        } else {
            return response()->json(['error' => 'Employee not found.'], 404);
        }
    }
}
