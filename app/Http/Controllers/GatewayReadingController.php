<?php

namespace App\Http\Controllers;

use App\Models\GatewayReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GatewayReadingController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request details (headers, payload, etc.)
        Log::info('Received Gateway Request', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Validate the incoming request data
        $validated = $request->validate([
            'transmitterSerialNumber' => 'required|string',
            'nodeType' => 'required|string',
            'reads' => 'required|array',
            'reads.*.timeStampUTC' => 'required|date',
            'reads.*.deviceUID' => 'required|string',
            'reads.*.manufacturerName' => 'required|string',
            'reads.*.distance' => 'required|integer',
            'reads.*.count' => 'required|integer',
            'allCount' => 'required|integer|min:0', // Assuming you want to validate this too
        ]);

        // Check if 'allCount' is greater than 0
        if ($request->input('allCount') > 0) {
            // Prepare data for batch insertion
            $gatewayReadingsData = [];
            $currentPKT = Carbon::now('Asia/Karachi')->format('Y-m-d H:i:s');

            foreach ($request->reads as $read) {
                // Replace the timeStampUTC with the current PKT timestamp
                $gatewayReadingsData[] = [
                    'transmitter_serial_number' => $request->transmitterSerialNumber,
                    'node_type' => $request->nodeType,
                    'device_uid' => $read['deviceUID'],
                    'manufacturer_name' => $read['manufacturerName'],
                    'distance' => $read['distance'],
                    'time_stamp_utc' => $currentPKT, // Use current PKT timestamp
                    'count' => $read['count'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            try {
                // Insert the readings into the database
                GatewayReading::insert($gatewayReadingsData);

                Log::info('Gateway readings stored successfully', ['data' => $gatewayReadingsData]);

                return response()->json(['message' => 'Gateway readings stored successfully.'], 201);
            } catch (\Exception $e) {
                // Log any errors during insertion
                Log::error('Error storing gateway readings', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }

        return response()->json(['message' => 'No readings to store.'], 400);
    }
}
