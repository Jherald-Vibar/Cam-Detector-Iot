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
        // ===== MQTT CONFIGURATION - MUST MATCH ESP32! =====
        private $mqttHost = '8c33605f8fe843f0a8bc3deca5d34911.s1.eu.hivemq.cloud';  // ✅ FIXED!
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

            Log::info("🎯 MQTT: Sending servo command - Pan: {$pan}, Tilt: {$tilt}");

            try {
                // Create MQTT connection settings
                $connectionSettings = (new ConnectionSettings)
                    ->setUsername($this->mqttUsername)
                    ->setPassword($this->mqttPassword)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true)
                    ->setConnectTimeout(10)
                    ->setKeepAliveInterval(60);

                // Create MQTT client with unique ID per request
                $clientId = $this->mqttClientId . '_' . time();
                $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, $clientId);

                // Connect to broker
                Log::info("🔌 MQTT: Connecting to {$this->mqttHost}:{$this->mqttPort}");
                $mqtt->connect($connectionSettings, true);
                Log::info("✅ MQTT: Connected successfully!");

                // Prepare message
                $message = json_encode([
                    'pan' => (int)$pan,
                    'tilt' => (int)$tilt,
                    'timestamp' => time(),
                    'source' => 'laravel'
                ]);

                // Publish to servo command topic
                $mqtt->publish(
                    'theodore/servo/command',
                    $message,
                    0,      // QoS level 0
                    false   // Not retained
                );

                Log::info("📤 MQTT: Message published - {$message}");

                // Give it a moment to ensure delivery
                usleep(100000); // 100ms

                // Disconnect cleanly
                $mqtt->disconnect();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Servo command sent via MQTT',
                    'pan' => $pan,
                    'tilt' => $tilt,
                    'method' => 'MQTT',
                    'broker' => $this->mqttHost,
                    'topic' => 'theodore/servo/command',
                    'payload' => json_decode($message, true)
                ]);

            } catch (\Exception $e) {
                Log::error("❌ MQTT Error: " . $e->getMessage());
                Log::error("Stack trace: " . $e->getTraceAsString());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send MQTT command',
                    'error' => $e->getMessage(),
                    'broker' => $this->mqttHost,
                    'port' => $this->mqttPort,
                    'troubleshooting' => [
                        'Check ESP32 Serial Monitor for MQTT connection status',
                        'Verify ESP32 is subscribed to theodore/servo/command',
                        'Test MQTT connection using getMqttStatus endpoint',
                        'Ensure both Laravel and ESP32 use same broker/credentials'
                    ]
                ], 500);
            }
        }

        /**
     * Update ngrok URL
     */
    /**
 * Update ngrok URL
 */
public function updateNgrokUrl(Request $request)
{
    try {
        $request->validate([
            'ngrok_url' => 'required|url|starts_with:https://'
        ]);

        $ngrokUrl = rtrim($request->input('ngrok_url'), '/');

        // Store in cache (24 hour expiry)
        Cache::put('ngrok_url', $ngrokUrl, now()->addHours(24));

        Log::info("🌐 ngrok URL updated: {$ngrokUrl}");

        return response()->json([
            'status' => 'success',
            'message' => 'ngrok URL updated successfully',
            'ngrok_url' => $ngrokUrl
        ]);

    } catch (\Exception $e) {
        Log::error("❌ Failed to update ngrok URL: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Remove ngrok URL (switch back to local IP)
     */
    public function removeNgrokUrl(Request $request)
    {
        try {
            // Remove from cache
            Cache::forget('ngrok_url');

            Log::info("🔄 Switched back to local IP mode");

            return response()->json([
                'status' => 'success',
                'message' => 'Switched back to local IP successfully'
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Failed to remove ngrok URL: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to switch to local IP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current stream URL (ngrok or local)
     */
    public function getStreamUrl(Request $request)
    {
        try {
            $ngrokUrl = Cache::get('ngrok_url');
            $cameraIp = Cache::get('esp32_camera_ip', '192.168.68.112');

            if ($ngrokUrl) {
                Log::info("📡 Using ngrok URL: {$ngrokUrl}");

                return response()->json([
                    'status' => 'success',
                    'base_url' => $ngrokUrl,
                    'snapshot_url' => $ngrokUrl . '/snapshot',
                    'using_ngrok' => true,
                    'mode' => 'remote'
                ]);
            }

            $localUrl = "http://{$cameraIp}";
            Log::info("📡 Using local IP: {$localUrl}");

            return response()->json([
                'status' => 'success',
                'base_url' => $localUrl,
                'snapshot_url' => $localUrl . '/snapshot',
                'using_ngrok' => false,
                'mode' => 'local'
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Failed to get stream URL: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve stream URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        /**
         * Get MQTT connection status
         */
        public function getMqttStatus()
        {
            Log::info("🔍 Testing MQTT connection to {$this->mqttHost}:{$this->mqttPort}");

            try {
                $connectionSettings = (new ConnectionSettings)
                    ->setUsername($this->mqttUsername)
                    ->setPassword($this->mqttPassword)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true)
                    ->setConnectTimeout(5);

                $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, 'Laravel_Status_Test_' . time());

                Log::info("🔌 Attempting connection...");
                $mqtt->connect($connectionSettings, true);

                Log::info("✅ Connected! Disconnecting...");
                $mqtt->disconnect();

                return response()->json([
                    'status' => 'success',
                    'message' => 'MQTT broker is reachable',
                    'broker' => $this->mqttHost,
                    'port' => $this->mqttPort,
                    'username' => $this->mqttUsername
                ]);

            } catch (\Exception $e) {
                Log::error("❌ MQTT Connection Failed: " . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot connect to MQTT broker',
                    'error' => $e->getMessage(),
                    'broker' => $this->mqttHost,
                    'port' => $this->mqttPort,
                    'hints' => [
                        'Check if broker URL is correct',
                        'Verify username and password',
                        'Ensure port 8883 is accessible from your server',
                        'Check if SSL/TLS certificates are valid'
                    ]
                ], 500);
            }
        }

        /**
         * Get camera status from MQTT
         */
        public function getCameraStatusMqtt()
        {
            try {
                $connectionSettings = (new ConnectionSettings)
                    ->setUsername($this->mqttUsername)
                    ->setPassword($this->mqttPassword)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true)
                    ->setConnectTimeout(5);

                $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, 'Laravel_Reader_' . time());
                $mqtt->connect($connectionSettings, true);

                $status = null;
                $messageReceived = false;

                // Subscribe to camera status topic
                $mqtt->subscribe('theodore/camera/status', function ($topic, $message) use (&$status, &$messageReceived) {
                    Log::info("📩 Received message from {$topic}: {$message}");
                    $status = json_decode($message, true);
                    $messageReceived = true;
                }, 0);

                Log::info("📡 Waiting for camera status...");

                // Wait for message (max 5 seconds)
                $startTime = time();
                while (!$messageReceived && (time() - $startTime) < 5) {
                    $mqtt->loop(true);
                    usleep(100000); // 100ms
                }

                $mqtt->disconnect();

                if ($status) {
                    return response()->json([
                        'status' => 'success',
                        'data' => $status,
                        'method' => 'MQTT',
                        'topic' => 'theodore/camera/status'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No status received from ESP32 within 5 seconds',
                        'hint' => 'ESP32 may not be connected to MQTT or not publishing status'
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
         * Send test MQTT message (for debugging)
         */
        public function testMqtt()
        {
            try {
                $connectionSettings = (new ConnectionSettings)
                    ->setUsername($this->mqttUsername)
                    ->setPassword($this->mqttPassword)
                    ->setUseTls(true)
                    ->setTlsSelfSignedAllowed(true)
                    ->setConnectTimeout(10);

                $mqtt = new MqttClient($this->mqttHost, $this->mqttPort, 'Laravel_Test_' . time());

                Log::info("🧪 Test: Connecting...");
                $mqtt->connect($connectionSettings, true);

                $testMessage = json_encode([
                    'pan' => 90,
                    'tilt' => 90,
                    'test' => true,
                    'timestamp' => time()
                ]);

                Log::info("🧪 Test: Publishing message...");
                $mqtt->publish('theodore/servo/command', $testMessage, 0);

                Log::info("🧪 Test: Message sent successfully!");
                usleep(200000); // 200ms delay

                $mqtt->disconnect();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Test message sent to ESP32',
                    'topic' => 'theodore/servo/command',
                    'payload' => json_decode($testMessage, true)
                ]);

            } catch (\Exception $e) {
                Log::error("🧪 Test Failed: " . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Test failed',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        /**
         * Test if ESP32 is reachable via HTTP
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
         * Get camera status via HTTP (backwards compatibility)
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
                'message' => 'Camera is not reachable via HTTP'
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
