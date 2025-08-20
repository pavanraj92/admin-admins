<?php

namespace admin\admins\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\admins\Requests\AdminCreateRequest;
use admin\admins\Requests\AdminUpdateRequest;
use admin\admin_auth\Models\Admin;
use admin\admin_role_permissions\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use admin\admins\Mail\WelcomeAdminMail;
use Illuminate\Support\Facades\Auth;

class AdminManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:admin_manager_list')->only(['index']);
        $this->middleware('admincan_permission:admin_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:admin_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:admin_manager_view')->only(['show']);
        $this->middleware('admincan_permission:admin_manager_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $authId = Auth::guard('admin')->id();
            $admins = Admin::where('id', '!=', $authId)
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(Admin::getPerPageLimit())
                ->withQueryString();

            return view('admin::admin.index', compact('admins'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admins: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $roles = Role::whereStatus('1')->get();
            return view('admin::admin.createOrEdit', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admins: ' . $e->getMessage());
        }
    }

    public function store(AdminCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            $plainPassword = \Str::random(8);
            $requestData['password'] = Hash::make($plainPassword);

            // Remove roles from requestData to prevent mass assignment error
            $roles = $requestData['role_ids'] ?? [];
            unset($requestData['role_ids']);
            // Create admin
            $admin = Admin::create($requestData);

            // Attach roles to pivot table (admin_role)
            if (!empty($roles)) {
                $admin->roles()->attach($roles); // assuming `roles()` is a belongsToMany relation
            }

            // Send welcome mail
            Mail::to($admin->email)->send(new WelcomeAdminMail($admin, $plainPassword));
            return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admins: ' . $e->getMessage());
        }
    }

    /**
     * show admin details
     */
    public function show(Admin $admin)
    {
        try {
            return view('admin::admin.show', compact('admin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admins: ' . $e->getMessage());
        }
    }

    public function edit(Admin $admin)
    {
        try {
            $roles = Role::whereStatus('1')->get();
            $assignedRoleIds = $admin->roles()->pluck('roles.id')->toArray();
            return view('admin::admin.createOrEdit', compact('admin', 'roles', 'assignedRoleIds'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admin for editing: ' . $e->getMessage());
        }
    }

    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        try {
            $requestData = $request->validated();

            $roleIds = $requestData['role_ids'] ?? [];
            unset($requestData['role_ids']);

            $admin->update($requestData);

            // Sync roles in pivot table (admin_role)
            $admin->roles()->sync($roleIds);

            return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load admin for editing: ' . $e->getMessage());
        }
    }

    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $admin = Admin::findOrFail($request->id);
            $admin->status = $request->status;
            $admin->save();

            // create status html dynamically        
            $dataStatus = $admin->status == '1' ? '0' : '1';
            $label = $admin->status == '1' ? 'Active' : 'InActive';
            $btnClass = $admin->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $admin->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.admins.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $admin->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
