<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyDocument;
use App\Models\OwnerMetrics;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PropertyController extends Controller
{
    /**
     * Browse All Public Properties
     */
    public function index(Request $request)
    {
        $query = Property::active()->with(['owner', 'images']);

        // Filtering
        if ($request->has('location')) $query->byLocation($request->location);
        if ($request->has('property_type')) $query->byType($request->property_type);
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }
        if ($request->has('search')) $query->searchable($request->search);

        $properties = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show Single Property
     */
    public function show($id)
    {
        $property = Property::with(['owner', 'images', 'documents'])->findOrFail($id);
        
        // Ensure status is active OR user is owner OR user is admin
        $user = auth()->user();
        $isOwner = $user && $user->id === $property->user_id;
        $isAdmin = $user && ($user->admin || $user->role === 'admin'); // Adjust admin check logic as per project

        if ($property->status !== 'active' && !$isOwner && !$isAdmin) {
            abort(404);
        }

        $property->increment('view_count');

        return view('properties.show', compact('property'));
    }

    /*
    |--------------------------------------------------------------------------
    | Owner Methods
    |--------------------------------------------------------------------------
    */

    public function ownerList()
    {
        $properties = Property::ownedBy(auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return view('owner.properties.list', compact('properties'));
    }

    public function ownerCreate()
    {
        return view('owner.properties.create');
    }

    public function ownerStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'minimum_investment' => 'required|numeric|min:0',
            'expected_return_percentage' => 'required|numeric',
            'property_type' => 'required|in:residential,commercial,mixed_use,land',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'documents.*' => 'file|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $property = Property::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'address' => $validated['address'],
                'price' => $validated['price'],
                'minimum_investment' => $validated['minimum_investment'],
                'expected_return_percentage' => $validated['expected_return_percentage'],
                'property_type' => $validated['property_type'],
                'status' => 'pending',
                'views' => 0,
            ]);

            // Handle Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store("properties/{$property->id}", 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_url' => Storage::url($path),
                        'is_featured' => $index === 0, // First image featured
                    ]);
                }
            }

            // Handle Documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $path = $doc->store("properties/{$property->id}/documents", 'public');
                    PropertyDocument::create([
                        'property_id' => $property->id,
                        'document_type' => 'other', // Default or from input
                        'file_url' => Storage::url($path),
                        'file_name' => $doc->getClientOriginalName(),
                    ]);
                }
            }

            // Metrics Update
            $metrics = OwnerMetrics::firstOrCreate(['user_id' => auth()->id()]);
            $metrics->increment('total_properties_count');
            $metrics->increment('pending_approvals');

            // Activity Log
            Activity::create([
                'user_id' => auth()->id(),
                'type' => 'property_created', // Assuming 'type' column exists, or 'activity_type'
                'description' => "Created property: {$property->title}",
                'subject_type' => Property::class,
                'subject_id' => $property->id,
            ]);

            // Notifications (Stub)
            // Mail::to(auth()->user())->send(new PropertyCreated($property));
            // Mail::to('admin@360winestate.com')->send(new AdminPropertyReview($property));

            DB::commit();
            return redirect()->route('owner.properties')->with('success', 'Property submitted for approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating property: ' . $e->getMessage())->withInput();
        }
    }

    public function ownerEdit($id)
    {
        $property = Property::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($property->status === 'sold') {
            return back()->with('error', 'Cannot edit sold properties.');
        }

        return view('owner.properties.edit', compact('property'));
    }

    public function ownerUpdate(Request $request, $id)
    {
        $property = Property::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $property->update($validated);
        
        // If it was active, set to under_review if critical fields changed?
        // Requirement: "Set status to 'under_review' (needs re-approval)"
        if ($property->status == 'active' || $property->status == 'rejected') {
            $property->update(['status' => 'under_review']);
        }

        // Handle Images addition/deletion (simplified)
        // ...

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'property_updated',
            'description' => "Updated property:{$property->title}",
            'subject_type' => Property::class,
            'subject_id' => $property->id,
        ]);

        return redirect()->route('owner.properties')->with('success', 'Property updated and submitted for review.');
    }

    public function ownerDelete($id)
    {
        $property = Property::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (!in_array($property->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Only pending or rejected properties can be deleted.');
        }

        $property->delete();
        
        OwnerMetrics::where('user_id', auth()->id())->decrement('total_properties_count');

        return redirect()->route('owner.properties')->with('success', 'Property deleted.');
    }
}
