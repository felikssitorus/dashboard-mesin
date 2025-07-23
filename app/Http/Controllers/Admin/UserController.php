<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\User;
use App\Models\Roles;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function getDataTableUser(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['line'])->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('line_name', function ($user) {
                    return $user->line?->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->rawColumns([
                    'action'
                ])
                ->make(true);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $lines = Line::all();
        $roles = Roles::all();
        return response()->json([
            'user' => $user,
            'lines' => $lines,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
       $userData = $request->validate([
            'fullname'     => 'required|string|max:255',
            'compCode'     => 'required|string|max:255',
            'employeId'    => 'required|string|unique:users,employeId,' . $id,
            'empTypeGroup' => 'required|string',
            'email'        => 'required|email|unique:users,email,' . $id,
            'email_backup' => 'nullable|email',
            'phone'        => 'nullable|string',
            'jobLvl'       => 'required|exists:roles,name',
            'jobTitle'     => 'required|string|max:255',
            'line_id'      => 'nullable|exists:lines,id',
            'groupKode'    => 'required|string|max:255',
            'groupName'    => 'required|string',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update($userData);

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Updated',
                'redirect' => route('admin.user.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Deleted',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('admin.user.index') 
            ]);
        }
    }

}
