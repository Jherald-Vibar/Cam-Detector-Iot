<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class CamController extends Controller
{
    // ===== MQTT CONFIGURATION =====
    private $mqttHost = 'abc123def456.s2.eu.hivemq.cloud';
    private $mqttPort = 8883;
    private $mqttUsername = 'theodore_admin';
    private $mqttPassword = '0529100804Miii';
    private $mqttClientId = 'Laravel_Server';

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

        Log::info("MQTT: Sending servo command - Pan: {$pan}, Tilt: {$tilt}");

        try {
            // Create MQTT connection settings
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($this->mqttUsername)
                ->setPassword($this->mqttPassword)
                ->setUseTls(true)  // Enable SSL/TLS
                ->setTlsSelfSignedAllowed(true)  // Allow self-signed certificates
                ->setConnectTimeout(5)  // 5 second timeout
                ->setKeepAliveInterval(60);  // Keep connection alive

            // Create MQTT client
            $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, $this->mqttClientId);

            // Connect to broker
            Log::info("MQTT: Connecting to broker...");
            $mqtt->connect($connectionSettings, true);
            Log::info("MQTT: Connected successfully!");

            // Prepare message
            $message = json_encode([
                'pan' => $pan,
                'tilt' => $tilt,
                'timestamp' => time()
            ]);

            // Publish to servo command topic
            $mqtt->publish(
                'theodore/servo/command',  // Topic
                $message,                   // Message
                0                          // QoS level 0 (fire and forget)
            );

            Log::info("MQTT: Message published successfully - {$message}");

            // Disconnect
            $mqtt->disconnect();

            return response()->json([
                'status' => 'success',
                'message' => 'Servo command sent via MQTT',
                'pan' => $pan,
                'tilt' => $tilt,
                'method' => 'MQTT',
                'broker' => $this->mqttHost
            ]);

        } catch (\Exception $e) {
            Log::error("MQTT Error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send MQTT command',
                'error' => $e->getMessage(),
                'troubleshooting' => [
                    '1. Check MQTT broker credentials in CamController.php',
                    '2. Verify ESP32 is connected to MQTT (check Serial Monitor)',
                    '3. Check broker URL and port are correct',
                    '4. Ensure SSL/TLS is configured (port 8883)',
                    '5. Verify HiveMQ cluster is running'
                ]
            ], 500);
        }
    }

    /**
     * Get MQTT connection status
     */
    public function getMqttStatus()
    {
        try {
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($this->mqttUsername)
                ->setPassword($this->mqttPassword)
                ->setUseTls(true)
                ->setTlsSelfSignedAllowed(true)
                ->setConnectTimeout(3);

            $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, 'Laravel_Status_Test');
            $mqtt->connect($connectionSettings, true);
            $mqtt->disconnect();

            return response()->json([
                'status' => 'success',
                'message' => 'MQTT broker is reachable',
                'broker' => $this->mqttHost,
                'port' => $this->mqttPort
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot connect to MQTT broker',
                'error' => $e->getMessage(),
                'broker' => $this->mqttHost
            ], 500);
        }
    }

    /**
     * Get camera status from MQTT (optional - ESP32 publishes status)
     */
    public function getCameraStatusMqtt()
    {
        try {
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($this->mqttUsername)
                ->setPassword($this->mqttPassword)
                ->setUseTls(true)
                ->setTlsSelfSignedAllowed(true);

            $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, 'Laravel_Status_Reader');
            $mqtt->connect($connectionSettings, true);

            $status = null;

            // Subscribe and wait for one message
            $mqtt->subscribe('theodore/camera/status', function ($topic, $message) use (&$status) {
                $status = json_decode($message, true);
            }, 0);

            // Wait for message (max 3 seconds)
            $mqtt->loop(true, true, 3);
            $mqtt->disconnect();

            if ($status) {
                return response()->json([
                    'status' => 'success',
                    'data' => $status,
                    'method' => 'MQTT'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No status received from ESP32 within 3 seconds',
                    'hint' => 'ESP32 may not be connected to MQTT'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error("MQTT Status Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get camera status via MQTT',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test if ESP32 is reachable (local HTTP - keep for backwards compatibility)
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
     * Get camera status (local HTTP - keep for backwards compatibility)
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
