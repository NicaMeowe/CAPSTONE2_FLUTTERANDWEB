<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardReviewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('preventia.firebase_auth_token', 'test-token');
        Config::set('preventia.firebase_url', 'https://firebase.test');
        Http::fake([
            'https://firebase.test/*' => Http::response(['ok' => true], 200),
        ]);
    }

    public function test_guests_cannot_write_review_actions(): void
    {
        $response = $this->postJson(route('admin.alerts.status', ['alertId' => 'alert-1']), [
            'status' => 'reviewed',
        ]);

        $response->assertRedirectToRoute('login');
        Http::assertNothingSent();
    }

    public function test_administrator_can_update_alert_status_in_firebase(): void
    {
        $response = $this->withSession(['preventia_admin' => true])
            ->postJson(route('admin.alerts.status', ['alertId' => 'alert-1']), [
                'status' => 'reviewed',
            ]);

        $response->assertOk()->assertJson([
            'ok' => true,
            'message' => 'The record was updated in Firebase.',
        ]);

        Http::assertSent(function (HttpRequest $request): bool {
            return $request->method() === 'PATCH'
                && $request->url() === 'https://firebase.test/alerts/alert-1.json?auth=test-token'
                && $request->data() === ['status' => 'Reviewed'];
        });
    }

    public function test_administrator_can_mark_visit_verified_reviewed_or_resolved(): void
    {
        foreach (['verified' => ['verified' => true], 'reviewed' => ['status' => 'Reviewed'], 'resolved' => ['status' => 'Resolved']] as $action => $updates) {
            $this->withSession(['preventia_admin' => true])
                ->postJson(route('admin.visits.review', ['visitId' => 'visit-1']), [
                    'action' => $action,
                ])
                ->assertOk()
                ->assertJson(['ok' => true]);

            Http::assertSent(function (HttpRequest $request) use ($updates): bool {
                return $request->method() === 'PATCH'
                    && $request->url() === 'https://firebase.test/visits/visit-1.json?auth=test-token'
                    && $request->data() === $updates;
            });
        }
    }

    public function test_invalid_review_actions_are_rejected_without_a_firebase_write(): void
    {
        $response = $this->withSession(['preventia_admin' => true])
            ->postJson(route('admin.visits.review', ['visitId' => 'visit-1']), [
                'action' => 'delete',
            ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['action']);
        Http::assertNothingSent();
    }
}