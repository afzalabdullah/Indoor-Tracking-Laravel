<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Assets;
use App\Models\Anchor; // Import the Anchor model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiteController extends Controller
{
    // Show the form to create a new site
    public function create()
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        // dd($userId);
        return view('sites.create');
    }

    // Store a new site
    // Store a new site
public function store(Request $request)
{
    $userId = Auth::id(); // Get the authenticated user's ID

    // Validate the incoming request
    $request->validate([
        'name' => 'required|unique:sites,name',
        'description' => 'required|string',
        'anchors' => 'required|array|min:3',
        'assets' => 'required|array|min:1',
        'assets.*.device_uid' => 'required|string|unique:assets,device_uid',
        'assets.*.device_icon' => 'required|string',
        'assets.*.device_name' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Store the image in the 'public/images' folder
    $imagePath = $request->file('image')->store('images', 'public');

    // Attempt to create a new site
    try {
        // Create new site
        $site = Site::create([
            'email' => Auth::user()->email, // Get email from authenticated user
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => asset('storage/' . $imagePath),
            'user_id' => $userId,// Assign the logged-in user's ID
        ]);

        // Store anchors and assets
        $this->storeAnchors($request->anchors, $site->id);
        $this->storeAssets($request->assets, $site->id);

        return redirect()->route('sites.index')->with('success', 'Site created successfully!');

    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Failed to create site:', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Failed to create site. Please try again later.');
    }
}


    // Get all sites by email
    public function index()
    {
        $userEmail = Auth::user()->email;
        $sites = Site::where('email', $userEmail)->get(['id', 'name', 'description', 'image_url']);

        return view('sites.index', compact('sites'));
    }
    public function live()
    {
        $userEmail = Auth::user()->email;
        // Fetch all sites from the database associated with the user's email
         $sites = Site::where('email', $userEmail)->get(); // Call get() to execute the query

        return view('sites.live', compact('sites')); // Pass the sites to the view
    }

    // Show the form to edit an existing site
    public function edit($id)
    {
        $site = Site::with(['anchors', 'assets'])->findOrFail($id);
        return view('sites.edit', compact('site'));
    }

    // Update an existing site
    public function update(Request $request, $id)
    {
        $site = Site::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:sites,name,' . $id,
            'description' => 'required|string',
            'anchors' => 'required|array|min:3',
            'assets' => 'required|array|min:1',
            'assets.*.device_uid' => 'required|string|unique:assets,device_uid,' . $site->id,
            'assets.*.device_icon' => 'required|string',
            'assets.*.device_name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Optional
        ]);

        // Update image if provided
        if ($request->hasFile('image')) {
            $this->updateImage($site, $request->file('image'));
        }

        // Update site details
        $site->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Update anchors and assets
        $this->updateAnchors($request->anchors, $site->id);
        $this->updateAssets($request->assets, $site->id);

        return redirect()->route('sites.index')->with('success', 'Site updated successfully!');
    }

    // Delete site
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site); // Use authorization for better practices

        // Delete associated anchors and assets
        $site->anchors()->delete();
        $site->assets()->delete();

        // Delete the site
        $site->delete();

        return redirect()->route('sites.index')->with('success', 'Site deleted successfully!');
    }

    // Show site details
    public function show($siteId)
    {
        $site = Site::with(['anchors', 'assets'])->findOrFail($siteId);
        // dd($site);
        // Calculate max width and height based on anchors' data
        $maxWidth = $site->anchors->max('x');
        $maxHeight = $site->anchors->max('y');

        return view('sites.show', compact('site', 'maxWidth', 'maxHeight'));
    }


    // Store anchors
    private function storeAnchors(array $anchors, $siteId)
    {
        foreach ($anchors as $anchorData) {
            $anchorData['site_id'] = $siteId; // Associate anchor with the site
            Anchor::create($anchorData);
        }
    }

    // Store assets
    private function storeAssets(array $assets, $siteId)
    {
        foreach ($assets as $assetData) {
            $assetData['site_id'] = $siteId; // Associate asset with the site
            Assets::create($assetData);
        }
    }

    // Update image
    private function updateImage(Site $site, $image)
    {
        // Optionally delete the old image
        if ($site->image_url) {
            $oldImagePath = str_replace(asset('storage/'), 'public/', $site->image_url);
            Storage::delete($oldImagePath);
        }

        $imagePath = $image->store('images', 'public');
        $site->image_url = asset('storage/' . $imagePath);
        $site->save();
    }

    // Update anchors
    private function updateAnchors(array $anchors, $siteId)
    {
        Anchor::where('site_id', $siteId)->delete(); // Delete existing anchors
        $this->storeAnchors($anchors, $siteId); // Store new anchors
    }

    // Update assets
    private function updateAssets(array $assets, $siteId)
    {
        Assets::where('site_id', $siteId)->delete(); // Delete existing assets
        $this->storeAssets($assets, $siteId); // Store new assets
    }
}
