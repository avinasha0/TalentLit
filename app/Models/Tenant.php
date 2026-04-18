<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\URL;

class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'subdomain_enabled',
        'website',
        'location',
        'company_size',
        'logo',
        'primary_color',
        'careers_enabled',
        'careers_intro',
        'consent_text',
        'timezone',
        'locale',
        'currency',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The users that belong to the tenant.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user');
    }

    /**
     * Get the tenant branding.
     */
    public function branding(): HasOne
    {
        return $this->hasOne(TenantBranding::class);
    }

    /**
     * Get the job openings for this tenant.
     */
    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class);
    }

    /**
     * Get the candidates for this tenant.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get the applications for this tenant.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the interviews for this tenant.
     */
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    /**
     * Get the tenant's subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(TenantSubscription::class);
    }

    /**
     * Get the tenant's active subscription.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(TenantSubscription::class)->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get the current subscription plan.
     */
    public function currentPlan()
    {
        return $this->activeSubscription?->plan;
    }

    /**
     * Check if tenant has Free plan.
     */
    public function hasFreePlan(): bool
    {
        $subscription = $this->activeSubscription;

        return $subscription && $subscription->plan && $subscription->plan->slug === 'free';
    }

    /**
     * Check if tenant has a specific feature.
     */
    public function hasFeature(string $feature): bool
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return false;
        }

        return $plan->$feature ?? false;
    }

    /**
     * Check if tenant is within a specific limit.
     */
    public function withinLimit(string $limit, int $currentCount): bool
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return false;
        }

        $maxLimit = $plan->$limit ?? 0;

        return $currentCount < $maxLimit;
    }

    /**
     * Get remaining limit for a specific feature.
     */
    public function getRemainingLimit(string $limit, int $currentCount): int
    {
        $plan = $this->currentPlan();

        if (! $plan) {
            return 0;
        }

        $maxLimit = $plan->$limit ?? 0;

        return max(0, $maxLimit - $currentCount);
    }

    /**
     * Get the dashboard URL for this tenant.
     * Returns subdomain URL if subdomain is enabled, otherwise path-based URL.
     */
    public function getDashboardUrl(): string
    {
        $planSlug = $this->activeSubscription?->plan?->slug;
        $shouldUseSubdomain = $this->usesEnterpriseSubdomain();

        \Log::info('Tenant.GetDashboardUrl.Decision', [
            'tenant_id' => $this->id,
            'tenant_slug' => $this->slug,
            'subdomain' => $this->subdomain,
            'subdomain_enabled' => $this->subdomain_enabled,
            'plan_slug' => $planSlug,
            'should_use_subdomain' => $shouldUseSubdomain,
        ]);

        // Use subdomain only when explicitly enabled and tenant is on Enterprise.
        if ($shouldUseSubdomain) {
            // Build subdomain URL
            $appUrl = config('app.url');
            $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'http';
            $port = parse_url($appUrl, PHP_URL_PORT);

            // Construct subdomain URL
            $subdomainUrl = $scheme.'://'.$this->subdomain.'.'.$appDomain;
            if ($port) {
                $subdomainUrl .= ':'.$port;
            }

            $finalUrl = $subdomainUrl.'/dashboard';
            \Log::info('Tenant.GetDashboardUrl.Result', [
                'tenant_id' => $this->id,
                'url_type' => 'subdomain',
                'url' => $finalUrl,
            ]);

            return $finalUrl;
        }

        // Use path-based routing for non-subdomain tenants
        $finalUrl = route('tenant.dashboard', $this->slug);
        \Log::info('Tenant.GetDashboardUrl.Result', [
            'tenant_id' => $this->id,
            'url_type' => 'path',
            'url' => $finalUrl,
        ]);

        return $finalUrl;
    }

    /**
     * Enterprise tenants that serve the app from a dedicated hiring subdomain.
     */
    public function usesEnterpriseSubdomain(): bool
    {
        $planSlug = $this->activeSubscription?->plan?->slug;

        return (bool) ($this->subdomain_enabled && $this->subdomain && $planSlug === 'enterprise');
    }

    /**
     * Applicant (candidate) sign-in URL (separate from staff web login).
     */
    public function getCandidateLoginUrl(): string
    {
        if ($this->usesEnterpriseSubdomain()) {
            $appUrl = config('app.url');
            $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'http';
            $port = parse_url($appUrl, PHP_URL_PORT);

            $subdomainUrl = $scheme.'://'.$this->subdomain.'.'.$appDomain;
            if ($port) {
                $subdomainUrl .= ':'.$port;
            }

            return $subdomainUrl.'/candidate/login';
        }

        return url(route('candidate.login', ['tenant' => $this->slug], false));
    }

    /**
     * Applicant (candidate) self-service area for this organization.
     */
    public function getApplicantPortalUrl(): string
    {
        $shouldUseSubdomain = $this->usesEnterpriseSubdomain();

        if ($shouldUseSubdomain) {
            $appUrl = config('app.url');
            $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'http';
            $port = parse_url($appUrl, PHP_URL_PORT);

            $subdomainUrl = $scheme.'://'.$this->subdomain.'.'.$appDomain;
            if ($port) {
                $subdomainUrl .= ':'.$port;
            }

            return $subdomainUrl.'/my-applications';
        }

        return route('tenant.applicant.dashboard', $this->slug);
    }

    /**
     * Base URL for enterprise hiring subdomains (scheme + host + optional port), or null when not applicable.
     */
    public function getSubdomainRootUrl(): ?string
    {
        if (! $this->usesEnterpriseSubdomain() || ! $this->subdomain) {
            return null;
        }

        $appUrl = config('app.url');
        $appDomain = parse_url($appUrl, PHP_URL_HOST) ?? 'localhost';
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'http';
        $port = parse_url($appUrl, PHP_URL_PORT);

        $subdomainUrl = $scheme.'://'.$this->subdomain.'.'.$appDomain;
        if ($port) {
            $subdomainUrl .= ':'.$port;
        }

        return $subdomainUrl;
    }

    /**
     * Run a callback while signed offer URLs use the tenant subdomain as the app root (enterprise only).
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public function withOfferSigningRoot(callable $callback): mixed
    {
        $root = $this->getSubdomainRootUrl();
        if ($root === null) {
            return $callback();
        }

        URL::forceRootUrl($root);
        try {
            return $callback();
        } finally {
            URL::forceRootUrl(null);
        }
    }
}
