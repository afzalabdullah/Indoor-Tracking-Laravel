<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assets;
use App\Models\Site;
use App\Models\Anchor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Query the total number of sites for the logged-in user
        $totalSites = $user->sites()->count();

        // Query the total number of devices (assets) for the logged-in user's sites
        $activeDevices = $user->assets()->count();

        // Query the total number of anchors across the user's sites
        $totalAnchors = Anchor::whereIn('site_id', $user->sites()->pluck('id'))->count();

        // Example logic to get the alert count
        $alertsCount = Assets::whereDate('created_at', Carbon::today())
            ->whereIn('site_id', $user->sites()->pluck('id'))
            ->count();

        // Query recent activities (last 5 activities) for the user's assets
        $recentActivities = Assets::whereIn('site_id', $user->sites()->pluck('id'))
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($asset) {
                return (object)[
                    'description' => "{$asset->device_name} (ID: {$asset->device_uid}) activity",
                    'created_at' => $asset->created_at
                ];
            });

        // Define labels and data for the activity chart
        $activityLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $activityData = Assets::selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->whereIn('site_id', $user->sites()->pluck('id'))
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('total')
            ->toArray();

        // Pass data to the view
        return view('dashboard.index', compact(
            'totalSites',
            'activeDevices',
            'totalAnchors',
            'alertsCount',
            'recentActivities',
            'activityLabels',
            'activityData'
        ));
    }
}
