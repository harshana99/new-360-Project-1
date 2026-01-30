<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Activity;
use App\Models\OwnerMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('owner');

        if ($request->has('status')) $query->where('status', $request->status);
        if ($request->has('search')) $query->searchable($request->search);

        $properties = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.properties.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::with(['owner', 'images', 'documents'])->findOrFail($id);
        return view('admin.properties.show', compact('property'));
    }

    public function approve($id)
    {
        // Check permission if needed (middleware handles basics)
        $property = Property::findOrFail($id);
        
        DB::transaction(function() use ($property) {
            $property->approveByAdmin(auth()->id());
            
            // Log Activity
            Activity::create([
                'user_id' => auth()->id(), // Admin ID
                'type' => 'property_approved',
                'description' => "Approved property: {$property->title}",
                'subject_type' => Property::class,
                'subject_id' => $property->id,
            ]);

            // Notify Owner (stub)
        });

        return back()->with('success', 'Property approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        
        $property = Property::findOrFail($id);
        
        DB::transaction(function() use ($property, $request) {
            $property->rejectByAdmin(auth()->id(), $request->reason);
            
            // Log Activity
            Activity::create([
                'user_id' => auth()->id(),
                'type' => 'property_rejected',
                'description' => "Rejected property: {$property->title}",
                'subject_type' => Property::class,
                'subject_id' => $property->id,
            ]);
            
            // Notify Owner
        });

        return back()->with('success', 'Property rejected.');
    }
}
