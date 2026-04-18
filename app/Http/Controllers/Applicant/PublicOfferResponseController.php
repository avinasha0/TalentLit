<?php

namespace App\Http\Controllers\Applicant;

use App\Actions\Applications\RecordOfferResponse;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PublicOfferResponseController extends Controller
{
    public function accept(Request $request, string $tenant, string $application): mixed
    {
        return $this->respond($tenant, $application, 'accept');
    }

    public function acceptSubdomain(Request $request, string $application): mixed
    {
        return $this->respond(null, $application, 'accept');
    }

    public function reject(Request $request, string $tenant, string $application): mixed
    {
        return $this->respond($tenant, $application, 'reject');
    }

    public function rejectSubdomain(Request $request, string $application): mixed
    {
        return $this->respond(null, $application, 'reject');
    }

    public function showDiscussionForm(Request $request, string $tenant, string $application): mixed
    {
        return $this->discussionForm($tenant, $application);
    }

    public function showDiscussionFormSubdomain(Request $request, string $application): mixed
    {
        return $this->discussionForm(null, $application);
    }

    public function submitDiscussion(Request $request, string $tenant, string $application): mixed
    {
        return $this->discussionSubmit($request, $tenant, $application);
    }

    public function submitDiscussionSubdomain(Request $request, string $application): mixed
    {
        return $this->discussionSubmit($request, null, $application);
    }

    private function resolveTenant(?string $tenantSlug): Tenant
    {
        $fromContext = tenant();
        if ($fromContext instanceof Tenant) {
            return $fromContext;
        }

        if ($tenantSlug) {
            return Tenant::query()->where('slug', $tenantSlug)->firstOrFail();
        }

        abort(404);
    }

    private function resolveApplication(Tenant $tenant, string $applicationId): Application
    {
        return Application::query()
            ->where('tenant_id', $tenant->id)
            ->whereKey($applicationId)
            ->firstOrFail();
    }

    private function respond(?string $tenantSlug, string $applicationId, string $action): mixed
    {
        $tenant = $this->resolveTenant($tenantSlug);
        $applicationModel = $this->resolveApplication($tenant, $applicationId);

        $result = app(RecordOfferResponse::class)->handle($tenant, $applicationModel, $action);

        return $this->resultView($tenant, $applicationModel->fresh(), $result, $action);
    }

    private function discussionForm(?string $tenantSlug, string $applicationId): mixed
    {
        $tenant = $this->resolveTenant($tenantSlug);
        $applicationModel = $this->resolveApplication($tenant, $applicationId);

        if (strtolower((string) $applicationModel->status) !== 'offered') {
            return view('offers.result', [
                'tenant' => $tenant,
                'application' => $applicationModel,
                'variant' => 'not_offered',
            ]);
        }

        if ($applicationModel->offer_responded_at) {
            return view('offers.result', [
                'tenant' => $tenant,
                'application' => $applicationModel,
                'variant' => 'already_responded',
            ]);
        }

        $expiration = now()->addDays(60);
        $submitUrl = $tenant->withOfferSigningRoot(function () use ($tenant, $applicationModel, $expiration) {
            if ($tenant->usesEnterpriseSubdomain()) {
                return URL::temporarySignedRoute('subdomain.offers.discussion.submit', $expiration, [
                    'application' => $applicationModel->id,
                ]);
            }

            return URL::temporarySignedRoute('tenant.offers.discussion.submit', $expiration, [
                'tenant' => $tenant->slug,
                'application' => $applicationModel->id,
            ]);
        });

        return view('offers.discussion', [
            'tenant' => $tenant,
            'application' => $applicationModel,
            'submitUrl' => $submitUrl,
        ]);
    }

    private function discussionSubmit(Request $request, ?string $tenantSlug, string $applicationId): mixed
    {
        $tenant = $this->resolveTenant($tenantSlug);
        $applicationModel = $this->resolveApplication($tenant, $applicationId);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:10000'],
        ]);

        $result = app(RecordOfferResponse::class)->handle(
            $tenant,
            $applicationModel,
            'discussion',
            $validated['message']
        );

        return $this->resultView($tenant, $applicationModel->fresh(), $result, 'discussion');
    }

    /**
     * @param  array{ok: bool, error?: string}  $result
     */
    private function resultView(Tenant $tenant, Application $application, array $result, string $action): mixed
    {
        if (! $result['ok']) {
            $variant = match ($result['error'] ?? '') {
                'already_responded' => 'already_responded',
                'not_offered' => 'not_offered',
                'message_required' => 'discussion_invalid',
                default => 'error',
            };

            return view('offers.result', [
                'tenant' => $tenant,
                'application' => $application,
                'variant' => $variant,
            ]);
        }

        $variant = match ($action) {
            'accept' => 'accepted',
            'reject' => 'rejected',
            'discussion' => 'discussion_saved',
            default => 'ok',
        };

        return view('offers.result', [
            'tenant' => $tenant,
            'application' => $application,
            'variant' => $variant,
        ]);
    }
}
