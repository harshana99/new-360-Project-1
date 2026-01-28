<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * User Management Controller (Admin)
 * 
 * Allows admins to:
 * - View all users
 * - View user details
 * - Edit user information
 * - Suspend/activate user accounts
 * - View user KYC submissions
 * - Review user activity logs
 */
class UserManagementController extends Controller
{
    /**
     * List all users with filters and search
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function listUsers(Request $request)
    {
        $admin = auth()->user()->admin;
        
        // Get filter parameters
        $search = $request->input('search');
        $membershipType = $request->input('membership_type');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Build query
        $query = User::query();
        
        // Search by name, email, or phone
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by membership type
        if ($membershipType) {
            $query->where('membership_type', $membershipType);
        }
        
        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }
        
        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);
        
        // Paginate results
        $users = $query->paginate(20);
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'approved' => User::where('status', User::STATUS_APPROVED)->count(),
            'pending' => User::whereIn('status', [User::STATUS_KYC_SUBMITTED, User::STATUS_UNDER_REVIEW])->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'owners' => User::where('membership_type', User::MEMBERSHIP_OWNER)->count(),
            'investors' => User::where('membership_type', User::MEMBERSHIP_INVESTOR)->count(),
            'marketers' => User::where('membership_type', User::MEMBERSHIP_MARKETER)->count(),
        ];
        
        return view('admin.users.index', compact(
            'users', 
            'stats', 
            'search', 
            'membershipType', 
            'status', 
            'sortBy', 
            'sortOrder'
        ));
    }

    /**
     * Show detailed user information
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function userDetails($id)
    {
        $admin = auth()->user()->admin;
        
        // Get user with relationships
        $user = User::with(['kycSubmissions', 'activities'])
            ->findOrFail($id);
        
        // Get latest KYC submission
        $kycSubmission = $user->getKYCSubmission();
        
        // Get recent activities (last 20)
        $recentActivities = $user->getAccountActivities(20);
        
        // Get user statistics based on membership type
        $userStats = $user->getOrCreateStats();
        
        return view('admin.users.show', compact('user', 'kycSubmission', 'recentActivities', 'userStats'));
    }

    /**
     * Show edit user form
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editUserForm($id)
    {
        $admin = auth()->user()->admin;
        
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user information
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $admin = auth()->user()->admin;
        $user = User::findOrFail($id);
        
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'membership_type' => ['required', 'in:owner,investor,marketer'],
            'status' => ['required', 'in:registered,membership_selected,kyc_submitted,under_review,approved,rejected,suspended'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);
        
        // Track what changed
        $changes = [];
        foreach ($validated as $key => $value) {
            if ($user->{$key} != $value) {
                $changes[$key] = [
                    'old' => $user->{$key},
                    'new' => $value
                ];
            }
        }
        
        // Update user
        $user->update($validated);
        
        // Log activity
        Activity::log(
            $user->id,
            Activity::TYPE_USER_UPDATED_BY_ADMIN,
            "User information updated by admin: {$admin->user->name}",
            $admin->id,
            ['changes' => $changes]
        );
        
        // TODO: Send email notification to user
        // Mail::to($user->email)->send(new ProfileUpdatedByAdminNotification($user, $changes));
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User information updated successfully!');
    }

    /**
     * Suspend user account
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspendUser(Request $request, $id)
    {
        $admin = auth()->user()->admin;
        $user = User::findOrFail($id);
        
        // Validate reason
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        // Check if already suspended
        if ($user->isSuspended()) {
            return back()->with('error', 'User is already suspended.');
        }
        
        // Suspend user
        $user->suspend($validated['reason'], $admin->id);
        
        // TODO: Send suspension email
        // Mail::to($user->email)->send(new AccountSuspendedNotification($user, $validated['reason']));
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User account suspended successfully.');
    }

    /**
     * Activate suspended user account
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateUser($id)
    {
        $admin = auth()->user()->admin;
        $user = User::findOrFail($id);
        
        // Check if suspended
        if (!$user->isSuspended()) {
            return back()->with('error', 'User account is not suspended.');
        }
        
        // Activate user
        $user->activate($admin->id);
        
        // TODO: Send activation email
        // Mail::to($user->email)->send(new AccountActivatedNotification($user));
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User account activated successfully.');
    }

    /**
     * View user's KYC submission details
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewUserKYC($id)
    {
        $admin = auth()->user()->admin;
        
        $user = User::findOrFail($id);
        $kycSubmission = $user->getKYCSubmission();
        
        if (!$kycSubmission) {
            return redirect()->route('admin.users.show', $user->id)
                ->with('error', 'User has not submitted KYC yet.');
        }
        
        // Get all KYC submissions for history
        $kycHistory = $user->kycSubmissions()->orderBy('created_at', 'desc')->get();
        
        return view('admin.users.kyc-details', compact('user', 'kycSubmission', 'kycHistory'));
    }

    /**
     * Delete user account (soft delete)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(Request $request, $id)
    {
        $admin = auth()->user()->admin;
        
        // Only super admin can delete users
        if ($admin->admin_role !== 'super_admin') {
            return back()->with('error', 'Only super administrators can delete user accounts.');
        }
        
        $user = User::findOrFail($id);
        
        // Validate reason
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'confirm_email' => ['required', 'email', function ($attribute, $value, $fail) use ($user) {
                if ($value !== $user->email) {
                    $fail('Email confirmation does not match user email.');
                }
            }],
        ]);
        
        // Log activity before deletion
        Activity::log(
            $user->id,
            Activity::TYPE_OTHER,
            "User account deleted by super admin: {$admin->user->name}",
            $admin->id,
            ['reason' => $validated['reason']]
        );
        
        // Soft delete user
        $user->delete();
        
        // TODO: Send deletion notification
        // Mail::to($user->email)->send(new AccountDeletedNotification($user, $validated['reason']));
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User account deleted successfully.');
    }

    /**
     * Restore deleted user account
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreUser($id)
    {
        $admin = auth()->user()->admin;
        
        // Only super admin can restore users
        if ($admin->admin_role !== 'super_admin') {
            return back()->with('error', 'Only super administrators can restore user accounts.');
        }
        
        $user = User::withTrashed()->findOrFail($id);
        
        if (!$user->trashed()) {
            return back()->with('error', 'User account is not deleted.');
        }
        
        // Restore user
        $user->restore();
        
        // Log activity
        Activity::log(
            $user->id,
            Activity::TYPE_OTHER,
            "User account restored by super admin: {$admin->user->name}",
            $admin->id
        );
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User account restored successfully.');
    }

    /**
     * Export users to CSV
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportUsers(Request $request)
    {
        $admin = auth()->user()->admin;
        
        // Get filters
        $membershipType = $request->input('membership_type');
        $status = $request->input('status');
        
        // Build query
        $query = User::query();
        
        if ($membershipType) {
            $query->where('membership_type', $membershipType);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $users = $query->get();
        
        // Log export activity
        Activity::log(
            $admin->user->id,
            Activity::TYPE_OTHER,
            "Admin exported {$users->count()} users to CSV",
            $admin->id,
            ['filters' => compact('membershipType', 'status')]
        );
        
        // Create CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_export_' . now()->format('Y-m-d_His') . '.csv"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Membership Type', 
                'Status', 'Created At', 'KYC Status', 'Last Login'
            ]);
            
            // Add data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    ucfirst($user->membership_type),
                    $user->getStatusLabel(),
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->hasKYC() ? 'Submitted' : 'Not Submitted',
                    $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
