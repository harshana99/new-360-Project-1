<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Admin Dashboard Controller
 * 
 * Handles all admin dashboard operations with role-based access control
 * 
 * Admin Roles:
 * - Super Admin: Full access + can create other admins
 * - Compliance Admin: KYC review only
 * - Finance Admin: Payments & commissions only
 * - Content Admin: Projects & content only
 */
class DashboardController extends Controller
{
    /**
     * Show admin dashboard based on role
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        $user = auth()->user();

        // Route to appropriate dashboard based on admin role
        switch ($admin->admin_role) {
            case Admin::ROLE_SUPER_ADMIN:
                return $this->superAdminDashboard($admin, $user);
            
            case Admin::ROLE_COMPLIANCE_ADMIN:
                return $this->complianceAdminDashboard($admin, $user);
            
            case Admin::ROLE_FINANCE_ADMIN:
                return $this->financeAdminDashboard($admin, $user);
            
            case Admin::ROLE_CONTENT_ADMIN:
                return $this->contentAdminDashboard($admin, $user);
            
            default:
                abort(403, 'Invalid admin role');
        }
    }

    /**
     * Super Admin Dashboard
     * Shows all metrics and full platform overview
     */
    private function superAdminDashboard($admin, $user)
    {
        $data = [
            'admin' => $admin,
            'user' => $user,
            'totalUsers' => User::count(),
            'approvedUsers' => User::where('status', 'approved')->count(),
            'activeAdmins' => Admin::active()->count(),
            'pendingKYC' => KycSubmission::where('status', 'submitted')->count(),
            'recentAdmins' => Admin::with('user')->latest()->take(5)->get(),
            'recentActivities' => $this->getRecentActivities(),
        ];

        return view('admin.dashboard.super_admin', $data);
    }

    /**
     * Compliance Admin Dashboard
     * Shows only KYC-related metrics
     */
    private function complianceAdminDashboard($admin, $user)
    {
        $data = [
            'admin' => $admin,
            'user' => $user,
            'pendingKYC' => KycSubmission::where('status', 'submitted')->count(),
            'approvedKYC' => KycSubmission::where('status', 'approved')->count(),
            'rejectedKYC' => KycSubmission::where('status', 'rejected')->count(),
            'recentKYC' => KycSubmission::with('user')->latest()->take(10)->get(),
        ];

        return view('admin.dashboard.compliance_admin', $data);
    }

    /**
     * Finance Admin Dashboard
     * Shows only financial metrics
     */
    private function financeAdminDashboard($admin, $user)
    {
        $data = [
            'admin' => $admin,
            'user' => $user,
            'totalPaid' => 0, // TODO: Implement when payment system is ready
            'pendingPayments' => 0,
            'monthTotal' => 0,
        ];

        return view('admin.dashboard.finance_admin', $data);
    }

    /**
     * Content Admin Dashboard
     * Shows only content-related metrics
     */
    private function contentAdminDashboard($admin, $user)
    {
        $data = [
            'admin' => $admin,
            'user' => $user,
            'totalProjects' => 0, // TODO: Implement when project system is ready
            'pendingProjects' => 0,
            'approvedProjects' => 0,
        ];

        return view('admin.dashboard.content_admin', $data);
    }

    /**
     * Show all users (accessible by all admin types)
     * 
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        $users = User::with('roles')->paginate(20);

        return view('admin.users.index', compact('admin', 'users'));
    }

    /**
     * Show all admins (Super Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function admins()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can access admin management');
        }

        $admins = Admin::with(['user', 'creator'])->latest()->get();

        return view('admin.admins.index', compact('admin', 'admins'));
    }

    /**
     * Show create admin form (Super Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function createAdminForm()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->canCreateAdmins()) {
            abort(403, 'Only Super Admin can create admins');
        }

        // Get users who are not already admins
        $availableUsers = User::whereDoesntHave('admin')
            ->where('status', 'approved')
            ->get();

        $roles = Admin::getCreatableRoles();

        return view('admin.admins.create', compact('admin', 'availableUsers', 'roles'));
    }

    /**
     * Store new admin (Super Admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAdmin(Request $request)
    {
        $currentAdmin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$currentAdmin->canCreateAdmins()) {
            abort(403, 'Only Super Admin can create admins');
        }

        // Validate request
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:admins,user_id'],
            'admin_role' => ['required', 'in:compliance_admin,finance_admin,content_admin'],
        ], [
            'user_id.required' => 'Please select a user',
            'user_id.exists' => 'Selected user does not exist',
            'user_id.unique' => 'This user is already an admin',
            'admin_role.required' => 'Please select an admin role',
            'admin_role.in' => 'Invalid admin role selected',
        ]);

        // Create admin record
        $newAdmin = Admin::create([
            'user_id' => $validated['user_id'],
            'admin_role' => $validated['admin_role'],
            'status' => Admin::STATUS_ACTIVE,
            'created_by' => auth()->id(),
        ]);

        $user = User::find($validated['user_id']);

        // Log the action
        Log::info('Super Admin created new admin', [
            'super_admin_id' => auth()->id(),
            'super_admin_name' => auth()->user()->name,
            'new_admin_id' => $newAdmin->id,
            'new_admin_user_id' => $user->id,
            'new_admin_name' => $user->name,
            'admin_role' => $validated['admin_role'],
        ]);

        // TODO: Send email notification to new admin
        // Mail::to($user->email)->send(new AdminCreatedNotification($newAdmin));

        return redirect()->route('admin.admins')
            ->with('success', "Successfully created {$newAdmin->getRoleLabel()} for {$user->name}");
    }

    /**
     * Show edit admin form (Super Admin only)
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editAdminForm($id)
    {
        $currentAdmin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$currentAdmin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can edit admins');
        }

        $adminToEdit = Admin::with('user')->findOrFail($id);
        
        // Cannot edit super admin
        if ($adminToEdit->isSuperAdmin()) {
            abort(403, 'Cannot edit Super Admin account');
        }

        $roles = Admin::getCreatableRoles();

        return view('admin.admins.edit', compact('currentAdmin', 'adminToEdit', 'roles'));
    }

    /**
     * Update admin (Super Admin only)
     * 
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin($id, Request $request)
    {
        $currentAdmin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$currentAdmin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can update admins');
        }

        $adminToUpdate = Admin::findOrFail($id);
        
        // Cannot update super admin
        if ($adminToUpdate->isSuperAdmin()) {
            abort(403, 'Cannot update Super Admin account');
        }

        $validated = $request->validate([
            'admin_role' => ['required', 'in:compliance_admin,finance_admin,content_admin'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $oldRole = $adminToUpdate->admin_role;
        $oldStatus = $adminToUpdate->status;

        $adminToUpdate->update($validated);

        // Log the changes
        Log::info('Super Admin updated admin', [
            'super_admin_id' => auth()->id(),
            'updated_admin_id' => $adminToUpdate->id,
            'old_role' => $oldRole,
            'new_role' => $validated['admin_role'],
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
        ]);

        // TODO: Send email if role changed
        // if ($oldRole !== $validated['admin_role']) {
        //     Mail::to($adminToUpdate->user->email)->send(new AdminRoleChangedNotification($adminToUpdate));
        // }

        return redirect()->route('admin.admins')
            ->with('success', 'Admin updated successfully');
    }

    /**
     * Deactivate admin (Super Admin only)
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivateAdmin($id)
    {
        $currentAdmin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$currentAdmin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can deactivate admins');
        }

        $adminToDeactivate = Admin::findOrFail($id);
        
        // Cannot deactivate super admin
        if ($adminToDeactivate->isSuperAdmin()) {
            abort(403, 'Cannot deactivate Super Admin account');
        }

        // Cannot deactivate yourself
        if ($adminToDeactivate->user_id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account');
        }

        $adminToDeactivate->update(['status' => Admin::STATUS_INACTIVE]);

        Log::info('Super Admin deactivated admin', [
            'super_admin_id' => auth()->id(),
            'deactivated_admin_id' => $adminToDeactivate->id,
        ]);

        return back()->with('success', 'Admin deactivated successfully');
    }

    /**
     * Activate admin (Super Admin only)
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateAdmin($id)
    {
        $currentAdmin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$currentAdmin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can activate admins');
        }

        $adminToActivate = Admin::findOrFail($id);
        $adminToActivate->update(['status' => Admin::STATUS_ACTIVE]);

        Log::info('Super Admin activated admin', [
            'super_admin_id' => auth()->id(),
            'activated_admin_id' => $adminToActivate->id,
        ]);

        return back()->with('success', 'Admin activated successfully');
    }

    /**
     * Show KYC management (Compliance Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function kyc()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->canReviewKYC()) {
            abort(403, 'Only Compliance Admin can access KYC management');
        }

        $pendingKYC = KycSubmission::with('user')
            ->where('status', 'submitted')
            ->latest()
            ->paginate(20);

        return view('admin.kyc.index', compact('admin', 'pendingKYC'));
    }

    /**
     * Show payments (Finance Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function payments()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->canManagePayments()) {
            abort(403, 'Only Finance Admin can access payments');
        }

        // TODO: Implement when payment system is ready
        return view('admin.payments.index', compact('admin'));
    }

    /**
     * Show commissions (Finance Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function commissions()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->canManagePayments()) {
            abort(403, 'Only Finance Admin can access commissions');
        }

        // TODO: Implement when commission system is ready
        return view('admin.commissions.index', compact('admin'));
    }

    /**
     * Show projects (Content Admin only)
     * 
     * @return \Illuminate\View\View
     */
    public function projects()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Only Content Admin can access projects');
        }

        // TODO: Implement when project system is ready
        return view('admin.projects.index', compact('admin'));
    }

    /**
     * Show analytics (All admin types)
     * 
     * @return \Illuminate\View\View
     */
    public function analytics()
    {
        $admin = Admin::where('user_id', auth()->id())->firstOrFail();

        // Each admin type sees different analytics
        $data = [
            'admin' => $admin,
        ];

        return view('admin.analytics.index', $data);
    }

    /**
     * Admin logout
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully');
    }

    /**
     * Get recent admin activities
     * 
     * @return array
     */
    private function getRecentActivities()
    {
        // TODO: Implement activity logging system
        return [];
    }
}
