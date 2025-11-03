<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CamController extends Controller
{
    public function registerCamera(Request $request)
    {
        $ip = $request->input('ip');

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid IP address'
            ], 400);
        }

        Cache::put('esp32_camera_ip', $ip, now()->addHours(24));

        Log::info("ESP32 Camera registered with IP: " . $ip);

        return response()->json([
            'status' => 'success',
            'ip' => $ip,
            'message' => 'Camera registered successfully'
        ]);
    }

    public function moveServo(Request $request)
    {
        $request->validate([
            'pan' => 'required|integer|min:0|max:180',
            'tilt' => 'required|integer|min:0|max:180'
        ]);

        $pan = $request->input('pan');
        $tilt = $request->input('tilt');

        $ip = Cache::get('esp32_camera_ip', '192.168.1.112');

        Log::info("Attempting to move servo to Pan: {$pan}, Tilt: {$tilt} via IP: {$ip}");

        // Test connection first
        if (!$this->testESP32Connection($ip)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ESP32 is not reachable. Check network connection.',
                'ip' => $ip,
                'solution' => 'Make sure ESP32 and Laravel are on the same network'
            ], 500);
        }

        try {
            $response = Http::timeout(3)
                ->retry(2, 100) // Retry twice with 100ms delay
                ->get("http://{$ip}/servo", [
                    'pan' => $pan,
                    'tilt' => $tilt
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Servo moved successfully: " . json_encode($data));

                return response()->json($data);
            } else {
                Log::error("ESP32 HTTP error: " . $response->status());
                return response()->json([
                    'status' => 'error',
                    'message' => 'ESP32 responded with HTTP error: ' . $response->status()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("ESP32 connection failed: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Connection to ESP32 failed',
                'error' => $e->getMessage(),
                'ip' => $ip,
                'troubleshooting' => [
                    '1. Check if ESP32 is powered on',
                    '2. Verify IP address is correct',
                    '3. Ensure both devices are on same network',
                    '4. Check firewall settings'
                ]
            ], 500);
        }
    }

    /**
     * Test if ESP32 is reachable
     */
    private function testESP32Connection($ip)
    {
        try {
            $response = Http::timeout(2)->get("http://{$ip}/");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get camera status
     */
    public function getCameraStatus()
    {
        $ip = Cache::get('esp32_camera_ip', '192.168.1.112');

        $isOnline = $this->testESP32Connection($ip);

        if ($isOnline) {
            try {
                $response = Http::timeout(2)->get("http://{$ip}/status");
                if ($response->successful()) {
                    return response()->json(array_merge(
                        $response->json(),
                        ['status' => 'online', 'reachable' => true]
                    ));
                }
            } catch (\Exception $e) {
                // Continue to offline response
            }
        }

        return response()->json([
            'status' => 'offline',
            'reachable' => false,
            'ip' => $ip,
            'message' => 'Camera is not reachable'
        ]);
    }

    /**
     * Dashboard view
     */
    public function dashboard()
    {
        $cameraIp = Cache::get('esp32_camera_ip', '192.168.1.112');
        $isReachable = $this->testESP32Connection($cameraIp);

        return view('User.dashboard', compact('cameraIp', 'isReachable'));
    }
}
