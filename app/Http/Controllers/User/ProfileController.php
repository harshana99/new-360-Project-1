<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\KycSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use ZipArchive;

/**
 * User Profile Controller
 * 
 * Handles all user profile management features including:
 * - Profile viewing and editing
 * - Password changes
 * - KYC status viewing and resubmission
 * - Account activity logs
 * - Document downloads
 */
class ProfileController extends Controller
{
    /**
     * Show user profile
     * 
     * @return \Illuminate\View\View
     */
    public function showProfile()
    {
        $user = auth()->user();
        
        // Get user's KYC submission if exists
        $kycSubmission = $user->getKYCSubmission();
        
        // Get recent activities (last 10)
        $recentActivities = $user->getAccountActivities(10);
        
        // Get last login
        $lastLogin = $user->getLastLogin();
        
        return view('user.profile.show', compact('user', 'kycSubmission', 'recentActivities', 'lastLogin'));
    }

    /**
     * Show edit profile form
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editProfileForm()
    {
        $user = auth()->user();
        
        // Check if user can edit profile (not suspended)
        if (!$user->canEditProfile()) {
            return redirect()->route('user.profile')
                ->with('error', 'Your account is suspended. You cannot edit your profile.');
        }
        
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Update user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        // Check if user can edit profile
        if (!$user->canEditProfile()) {
            return redirect()->route('user.profile')
                ->with('error', 'Your account is suspended. You cannot edit your profile.');
        }
        
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'bio' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone number is required',
            'date_of_birth.before' => 'You must be at least 18 years old',
        ]);
        
        // Update user
        $user->update($validated);
        
        // Log activity
        $user->logActivity(
            Activity::TYPE_PROFILE_UPDATE,
            'User updated their profile information',
            ['updated_fields' => array_keys($validated)]
        );
        
        // TODO: Send email confirmation
        // Mail::to($user->email)->send(new ProfileUpdatedNotification($user));
        
        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     * 
     * @return \Illuminate\View\View
     */
    public function changePasswordForm()
    {
        return view('user.profile.change-password');
    }

    /**
     * Change user password
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        
        // Validate input
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'new_password_confirmation' => ['required'],
        ], [
            'current_password.required' => 'Current password is required',
            'new_password.required' => 'New password is required',
            'new_password.confirmed' => 'Password confirmation does not match',
        ]);
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);
        
        // Log activity
        $user->logActivity(
            Activity::TYPE_PASSWORD_CHANGED,
            'User changed their password'
        );
        
        // TODO: Send security email
        // Mail::to($user->email)->send(new PasswordChangedNotification($user));
        
        return redirect()->route('user.profile')
            ->with('success', 'Password changed successfully! Please use your new password for future logins.');
    }

    /**
     * View KYC status
     * 
     * @return \Illuminate\View\View
     */
    public function viewKYCStatus()
    {
        $user = auth()->user();
        
        // Get user's KYC submission
        $kycSubmission = $user->getKYCSubmission();
        
        if (!$kycSubmission) {
            return redirect()->route('kyc.submit')
                ->with('info', 'You have not submitted KYC yet. Please complete your KYC verification.');
        }
        
        return view('user.kyc.status', compact('kycSubmission'));
    }

    /**
     * Show KYC resubmission form
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function resubmitKYCForm()
    {
        $user = auth()->user();
        
        // Get previous KYC submission
        $previousKyc = $user->getKYCSubmission();
        
        if (!$previousKyc) {
            return redirect()->route('kyc.submit')
                ->with('info', 'You have not submitted KYC yet.');
        }
        
        // Check if KYC is rejected or resubmission required
        if (!in_array($previousKyc->status, ['rejected', 'resubmission_required'])) {
            return redirect()->route('user.kyc.status')
                ->with('info', 'Your KYC is ' . $previousKyc->status . '. Resubmission is not required.');
        }
        
        return view('user.kyc.resubmit', compact('previousKyc'));
    }

    /**
     * Submit resubmitted KYC
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitResubmittedKYC(Request $request)
    {
        $user = auth()->user();
        
        // Validate form data
        $validated = $request->validate([
            'id_type' => ['required', 'in:passport,drivers_license,national_id'],
            'id_number' => ['required', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'id_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'address_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ]);
        
        // Upload documents
        $idImagePath = $request->file('id_image')->store('kyc/id_images', 'public');
        $addressProofPath = $request->file('address_proof')->store('kyc/address_proofs', 'public');
        
        // Mark previous submission as superseded (optional)
        $previousKyc = $user->getKYCSubmission();
        if ($previousKyc) {
            $previousKyc->update(['status' => 'superseded']);
        }
        
        // Create new KYC submission
        $kycSubmission = KycSubmission::create([
            'user_id' => $user->id,
            'id_type' => $validated['id_type'],
            'id_number' => $validated['id_number'],
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => $validated['country'],
            'postal_code' => $validated['postal_code'],
            'id_image_path' => $idImagePath,
            'address_proof_path' => $addressProofPath,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        
        // Update user status
        $user->update(['status' => User::STATUS_KYC_SUBMITTED]);
        
        // Log activity
        $user->logActivity(
            Activity::TYPE_KYC_RESUBMITTED,
            'User resubmitted KYC documents',
            ['kyc_id' => $kycSubmission->id]
        );
        
        // TODO: Send email notification
        // Mail::to($user->email)->send(new KYCResubmittedNotification($user));
        
        return redirect()->route('user.kyc.status')
            ->with('success', 'KYC resubmitted successfully! Your documents are now under review.');
    }

    /**
     * View account activity log
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function viewAccountActivity(Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $activityType = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Build query
        $query = $user->activities()->with('admin')->latest();
        
        // Apply filters
        if ($activityType) {
            $query->where('activity_type', $activityType);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Paginate results
        $activities = $query->paginate(20);
        
        // Get available activity types for filter
        $activityTypes = Activity::select('activity_type')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('activity_type');
        
        return view('user.activity.index', compact('activities', 'activityTypes', 'activityType', 'dateFrom', 'dateTo'));
    }

    /**
     * Download KYC documents as ZIP
     * 
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadKYCDocuments()
    {
        $user = auth()->user();
        
        // Get KYC submission
        $kycSubmission = $user->getKYCSubmission();
        
        if (!$kycSubmission) {
            return redirect()->route('user.kyc.status')
                ->with('error', 'No KYC documents found.');
        }
        
        // Create ZIP file
        $zipFileName = 'kyc_documents_' . $user->id . '_' . now()->format('YmdHis') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add ID image
            if ($kycSubmission->id_image_path && Storage::disk('public')->exists($kycSubmission->id_image_path)) {
                $zip->addFile(
                    Storage::disk('public')->path($kycSubmission->id_image_path),
                    'id_document.' . pathinfo($kycSubmission->id_image_path, PATHINFO_EXTENSION)
                );
            }
            
            // Add address proof
            if ($kycSubmission->address_proof_path && Storage::disk('public')->exists($kycSubmission->address_proof_path)) {
                $zip->addFile(
                    Storage::disk('public')->path($kycSubmission->address_proof_path),
                    'address_proof.' . pathinfo($kycSubmission->address_proof_path, PATHINFO_EXTENSION)
                );
            }
            
            $zip->close();
        }
        
        // Log activity
        $user->logActivity(
            Activity::TYPE_OTHER,
            'User downloaded KYC documents'
        );
        
        // Download and delete temp file
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }
}
