<?php

namespace App\Http\Controllers;

use App\Services\FirebaseDatabase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function __construct(private readonly FirebaseDatabase $firebase)
    {
    }

    public function index(): View
    {
        return $this->render('dashboard', 'Overview');
    }

    public function visits(): View
    {
        return $this->render('visits', 'Visit history');
    }

    public function representatives(): View
    {
        return $this->render('representatives', 'Representatives');
    }

    public function facilities(): View
    {
        return $this->render('facilities', 'Facilities');
    }

    public function analytics(): View
    {
        return $this->render('analytics', 'Predictive analytics');
    }

    public function alerts(): View
    {
        return $this->render('alerts', 'Alerts & requests');
    }

    public function settings(): View
    {
        return view('dashboard.app', [
            'page' => 'settings',
            'pageTitle' => 'Settings',
            'payload' => $this->firebase->dashboardPayload(),
        ]);
    }

    public function updateAlert(Request $request, string $alertId): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['open', 'reviewed', 'resolved'])],
        ]);

        $result = $this->firebase->updateAlertStatus($alertId, $validated['status']);

        return response()->json(
            $result,
            $result['ok'] ? 200 : 422,
        );
    }

    public function updateVisit(Request $request, string $visitId): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', Rule::in(['verified', 'reviewed', 'resolved'])],
        ]);

        $result = $this->firebase->updateVisit($visitId, $validated['action']);

        return response()->json(
            $result,
            $result['ok'] ? 200 : 422,
        );
    }

    private function render(string $page, string $pageTitle): View
    {
        return view('dashboard.app', [
            'page' => $page,
            'pageTitle' => $pageTitle,
            'payload' => $this->firebase->dashboardPayload(),
        ]);
    }
}