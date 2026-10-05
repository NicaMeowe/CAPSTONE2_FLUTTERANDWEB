<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class FirebaseDatabase
{
    private string $baseUrl;
    private ?string $authToken;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('preventia.firebase_url'), '/');
        $this->authToken = config('preventia.firebase_auth_token') ?: null;
    }

    public function dashboardPayload(): array
    {
        $collections = [];
        $errors = [];

        foreach (['visits', 'representatives', 'facilities', 'alerts', 'medicines'] as $collection) {
            $result = $this->collection($collection);
            $collections[$collection] = $result['items'];

            if ($result['error']) {
                $errors[] = $result['error'];
            }
        }

        $isDemo = ! $this->authToken || count($errors) > 0;

        if ($isDemo) {
            $collections = $this->demoCollections();
        }

        return [
            'collections' => $collections,
            'stats' => $this->stats($collections),
            'connection' => [
                'mode' => $isDemo ? 'demo' : 'live',
                'label' => $isDemo ? 'Demo data' : 'Firebase live',
                'databaseUrl' => $this->baseUrl,
                'errors' => array_values(array_unique($errors)),
            ],
        ];
    }

    public function updateAlertStatus(string $alertId, string $status): array
    {
        return $this->write("alerts/{$alertId}", [
            'status' => $this->displayStatus($status),
        ]);
    }

    public function updateVisit(string $visitId, string $action): array
    {
        $updates = match ($action) {
            'verified' => ['verified' => true],
            'reviewed' => ['status' => 'Reviewed'],
            'resolved' => ['status' => 'Resolved'],
            default => null,
        };

        if ($updates === null) {
            return ['ok' => false, 'message' => 'That visit action is not supported.'];
        }

        return $this->write("visits/{$visitId}", $updates);
    }

    private function collection(string $path): array
    {
        if (! $this->authToken) {
            return ['items' => [], 'error' => 'Firebase auth token is not configured.'];
        }

        try {
            $response = $this->request('get', $path);

            if (! $response->successful()) {
                return [
                    'items' => [],
                    'error' => sprintf('Firebase %s returned HTTP %s.', $path, $response->status()),
                ];
            }

            $data = $response->json();

            if ($data === null) {
                return ['items' => [], 'error' => null];
            }

            if (! is_array($data)) {
                return ['items' => [], 'error' => sprintf('Firebase %s did not return a collection.', $path)];
            }

            return ['items' => $this->normalizeCollection($data), 'error' => null];
        } catch (Throwable $exception) {
            return ['items' => [], 'error' => 'Firebase could not be reached.'];
        }
    }

    private function write(string $path, array $updates): array
    {
        if (! $this->authToken) {
            return [
                'ok' => false,
                'message' => 'Firebase live access is not configured. Review actions are disabled in preview mode.',
            ];
        }

        try {
            $response = $this->request('patch', $path, $updates);

            if (! $response->successful()) {
                return [
                    'ok' => false,
                    'message' => sprintf('Firebase returned HTTP %s while saving this update.', $response->status()),
                ];
            }

            return ['ok' => true, 'message' => 'The record was updated in Firebase.'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => 'Firebase could not be reached. The update was not saved.'];
        }
    }

    private function request(string $method, string $path, array $data = []): Response
    {
        $request = Http::timeout(8)->acceptJson();

        if ($this->authToken) {
            $request = $request->withQueryParameters(['auth' => $this->authToken]);
        }

        return $request->$method("{$this->baseUrl}/{$path}.json", $data);
    }

    private function displayStatus(string $status): string
    {
        return match ($status) {
            'open' => 'Open',
            'reviewed' => 'Reviewed',
            'resolved' => 'Resolved',
            default => ucfirst($status),
        };
    }

    private function normalizeCollection(array $data): array
    {
        $items = [];

        foreach ($data as $id => $item) {
            if (! is_array($item)) {
                continue;
            }

            $items[] = array_merge(['id' => (string) $id], $item);
        }

        return $items;
    }

    private function stats(array $collections): array
    {
        $visits = $collections['visits'];
        $representatives = $collections['representatives'];
        $facilities = $collections['facilities'];
        $alerts = $collections['alerts'];

        $completed = count(array_filter($visits, fn (array $visit) => strtolower((string) ($visit['status'] ?? '')) === 'completed'));
        $attendance = count(array_filter($visits, fn (array $visit) => in_array(strtolower((string) ($visit['status'] ?? '')), ['checked-in', 'completed'], true)));
        $activeAlerts = count(array_filter($alerts, fn (array $alert) => strtolower((string) ($alert['status'] ?? 'open')) !== 'resolved'));

        return [
            'totalVisits' => count($visits),
            'completedVisits' => $completed,
            'attendanceRate' => count($visits) > 0 ? (int) round(($attendance / count($visits)) * 100) : 0,
            'activeRepresentatives' => count(array_filter($representatives, fn (array $rep) => strtolower((string) ($rep['status'] ?? 'active')) === 'active')),
            'facilityCount' => count($facilities),
            'activeAlerts' => $activeAlerts,
        ];
    }

    private function demoCollections(): array
    {
        return [
            'visits' => [
                ['id' => 'visit-1', 'representative' => 'Mara Santos', 'facility' => 'Makati Medical Center', 'doctor' => 'Dr. Luis Reyes', 'status' => 'Completed', 'time' => '08:42', 'date' => 'Today', 'location' => '14.5586, 121.0146', 'duration' => '36 min', 'verified' => true],
                ['id' => 'visit-2', 'representative' => 'Carlo Mendoza', 'facility' => 'St. Luke’s Medical Center', 'doctor' => 'Dr. Nina Cruz', 'status' => 'Checked-in', 'time' => '09:18', 'date' => 'Today', 'location' => '14.6248, 121.0212', 'duration' => '18 min', 'verified' => true],
                ['id' => 'visit-3', 'representative' => 'Ivy Navarro', 'facility' => 'Asian Hospital', 'doctor' => 'Dr. Paolo Lim', 'status' => 'Review', 'time' => 'Yesterday', 'date' => 'Sep 22', 'location' => '14.4197, 121.0359', 'duration' => '—', 'verified' => false],
                ['id' => 'visit-4', 'representative' => 'Jules Garcia', 'facility' => 'Makati Health Center', 'doctor' => 'Dr. Andrea Tan', 'status' => 'Completed', 'time' => 'Yesterday', 'date' => 'Sep 22', 'location' => '14.5658, 121.0123', 'duration' => '42 min', 'verified' => true],
                ['id' => 'visit-5', 'representative' => 'Mara Santos', 'facility' => 'The Medical City', 'doctor' => 'Dr. Bea Ong', 'status' => 'Missed call', 'time' => 'Sep 21', 'date' => 'Sep 21', 'location' => '14.6390, 121.0786', 'duration' => '—', 'verified' => false],
            ],
            'representatives' => [
                ['id' => 'rep-1', 'name' => 'Mara Santos', 'territory' => 'Makati Central', 'status' => 'Active', 'visits' => 18, 'coverage' => 92, 'lastSeen' => '2 min ago'],
                ['id' => 'rep-2', 'name' => 'Carlo Mendoza', 'territory' => 'Quezon City South', 'status' => 'Active', 'visits' => 15, 'coverage' => 84, 'lastSeen' => '8 min ago'],
                ['id' => 'rep-3', 'name' => 'Ivy Navarro', 'territory' => 'Pasig East', 'status' => 'Active', 'visits' => 13, 'coverage' => 78, 'lastSeen' => '21 min ago'],
                ['id' => 'rep-4', 'name' => 'Jules Garcia', 'territory' => 'Taguig North', 'status' => 'On leave', 'visits' => 9, 'coverage' => 71, 'lastSeen' => 'Yesterday'],
            ],
            'facilities' => [
                ['id' => 'facility-1', 'name' => 'Makati Medical Center', 'type' => 'Hospital', 'territory' => 'Makati Central', 'doctors' => 26, 'stock' => 'Healthy', 'coverage' => 88],
                ['id' => 'facility-2', 'name' => 'St. Luke’s Medical Center', 'type' => 'Hospital', 'territory' => 'Quezon City South', 'doctors' => 31, 'stock' => 'Monitor', 'coverage' => 76],
                ['id' => 'facility-3', 'name' => 'Asian Hospital', 'type' => 'Hospital', 'territory' => 'Muntinlupa West', 'doctors' => 18, 'stock' => 'Low stock', 'coverage' => 64],
                ['id' => 'facility-4', 'name' => 'Makati Health Center', 'type' => 'Clinic', 'territory' => 'Makati Central', 'doctors' => 11, 'stock' => 'Healthy', 'coverage' => 91],
            ],
            'alerts' => [
                ['id' => 'alert-1', 'title' => 'Low stock reported', 'detail' => 'Asian Hospital · Cefuroxime 500mg', 'severity' => 'high', 'status' => 'Open', 'time' => '14 min ago'],
                ['id' => 'alert-2', 'title' => 'Visit needs review', 'detail' => 'Ivy Navarro · Asian Hospital', 'severity' => 'medium', 'status' => 'Open', 'time' => '1 hr ago'],
                ['id' => 'alert-3', 'title' => 'Missed call submitted', 'detail' => 'Mara Santos · The Medical City', 'severity' => 'low', 'status' => 'Open', 'time' => 'Yesterday'],
            ],
            'medicines' => [
                ['id' => 'medicine-1', 'name' => 'Cefuroxime 500mg', 'facility' => 'Asian Hospital', 'status' => 'Low stock'],
                ['id' => 'medicine-2', 'name' => 'Atorvastatin 20mg', 'facility' => 'St. Luke’s Medical Center', 'status' => 'Monitor'],
            ],
        ];
    }
}