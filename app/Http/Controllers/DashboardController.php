<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Example data to pass to the view
        $totalLocations = 25; // Replace this with your logic to get the actual count
        $activeDevices = 12; // Replace with logic to get active devices
        $totalUsers = 150; // Replace with your logic to get total users
        $alertsCount = 5; // Replace with your logic to get alert count
        $recentActivities = [
            (object) ['description' => 'Device 001 activated', 'created_at' => now()],
            (object) ['description' => 'Device 002 deactivated', 'created_at' => now()->subMinutes(10)],
            // Add more activities as needed
        ];

        // Example data for activity chart
        $activityLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $activityData = [5, 10, 15, 20];

        return view('dashboard.index', compact(
            'totalLocations',
            'activeDevices',
            'totalUsers',
            'alertsCount',
            'recentActivities',
            'activityLabels',
            'activityData'
        ));
    }
}
