<?php

namespace App\Services;

use App\Models\Tenant;
use App\Support\Tenancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OnboardingViewLogService
{
    /**
     * Log a view event for employee onboarding
     *
     * @param string $eventType One of: Page.View, Onboarding.SlideOver.Open, Onboarding.SlideOver.Close, Onboarding.Tab.View
     * @param Request $request The HTTP request
     * @param array $extra Additional context data (e.g., ['tab' => 'Overview', 'query' => 'search=raj'])
     * @param string|null $resourceId The onboarding/candidate ID when applicable
     * @param string|null $resourceType The resource type (onboarding, candidate, tab, page)
     */
    public static function log(
        string $eventType,
        Request $request,
        array $extra = [],
        ?string $resourceId = null,
        ?string $resourceType = null
    ): void {
        try {
            $tenant = tenant() ?? Tenancy::get();
            $user = auth()->user();
            
            // Determine tenant source (subdomain or slug)
            $tenantSource = 'slug'; // default
            $routeName = $request->route()?->getName() ?? '';
            if (str_starts_with($routeName, 'subdomain.')) {
                $tenantSource = 'subdomain';
            } elseif ($request->route('tenant')) {
                $tenantSource = 'slug';
            }
            
            // Get actor information
            $actorUserId = $user?->id;
            $actorName = $user ? trim(($user->name ?? '') . ' ' . ($user->email ?? '')) : null;
            $actorRole = null;
            
            // Try to get user role if available
            if ($user && $tenant) {
                try {
                    $userRole = DB::table('custom_user_roles')
                        ->where('user_id', $user->id)
                        ->where('tenant_id', $tenant->id)
                        ->value('role_name');
                    $actorRole = $userRole ?? null;
                } catch (\Exception $e) {
                    // Role lookup failed, continue without it
                }
            }
            
            // If anonymous, use IP as identifier
            if (!$actorUserId) {
                $actorName = 'Anonymous';
                $actorRole = 'Guest';
            }
            
            // Get request metadata
            $url = $request->fullUrl();
            $userAgent = substr($request->userAgent() ?? 'Unknown', 0, 200); // Limit length
            $ipAddress = $request->ip() ?? '0.0.0.0';
            
            // Build log data
            $logData = [
                'timestamp' => now()->utc()->toIso8601String(),
                'actor_user_id' => $actorUserId,
                'actor_name' => $actorName,
                'actor_role' => $actorRole,
                'tenant' => $tenant?->slug ?? 'unknown',
                'tenant_source' => $tenantSource,
                'event_type' => $eventType,
                'resource_type' => $resourceType ?? self::inferResourceType($eventType),
                'resource_id' => $resourceId,
                'url' => $url,
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'extra' => $extra,
            ];
            
            // Write human-readable log
            $humanLog = self::formatHumanLog($logData);
            Log::info($humanLog);
            
            // Write structured JSON log
            Log::channel('daily')->info('OnboardingViewEvent', $logData);
            
            // Optionally write to database (if table exists)
            try {
                self::writeToDatabase($logData, $tenant);
            } catch (\Exception $e) {
                // Database write failed, log warning but don't fail the request
                Log::warning('Failed to write onboarding view log to database', [
                    'error' => $e->getMessage(),
                    'event_type' => $eventType,
                ]);
            }
            
        } catch (\Exception $e) {
            // Logging should never break the application
            Log::error('Failed to log onboarding view event', [
                'error' => $e->getMessage(),
                'event_type' => $eventType,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    /**
     * Format human-readable log line
     */
    private static function formatHumanLog(array $data): string
    {
        $parts = [
            "[{$data['timestamp']}]",
            $data['event_type'],
            "tenant={$data['tenant']}",
            "source={$data['tenant_source']}",
        ];
        
        if ($data['actor_user_id']) {
            $parts[] = "user={$data['actor_user_id']}";
            if ($data['actor_name']) {
                $parts[] = "({$data['actor_name']}";
                if ($data['actor_role']) {
                    $parts[] = ", {$data['actor_role']}";
                }
                $parts[] = ")";
            }
        } else {
            $parts[] = "user=anonymous";
        }
        
        $parts[] = "url={$data['url']}";
        $parts[] = "ip={$data['ip_address']}";
        
        if ($data['resource_id']) {
            $parts[] = "resource_id={$data['resource_id']}";
        }
        
        if (!empty($data['extra'])) {
            $extraStr = http_build_query($data['extra']);
            if ($extraStr) {
                $parts[] = "extra={$extraStr}";
            }
        }
        
        return implode(' ', $parts);
    }
    
    /**
     * Infer resource type from event type
     */
    private static function inferResourceType(string $eventType): string
    {
        if ($eventType === 'Page.View') {
            return 'page';
        } elseif (str_contains($eventType, 'SlideOver')) {
            return 'onboarding';
        } elseif (str_contains($eventType, 'Tab')) {
            return 'tab';
        }
        return 'onboarding';
    }
    
    /**
     * Write log to database (if table exists)
     */
    private static function writeToDatabase(array $logData, ?Tenant $tenant): void
    {
        // Check if table exists
        if (!DB::getSchemaBuilder()->hasTable('onboarding_view_logs')) {
            return; // Table doesn't exist, skip database write
        }
        
        DB::table('onboarding_view_logs')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'tenant_id' => $tenant?->id,
            'actor_user_id' => $logData['actor_user_id'],
            'actor_name' => $logData['actor_name'],
            'actor_role' => $logData['actor_role'],
            'tenant_slug' => $logData['tenant'],
            'tenant_source' => $logData['tenant_source'],
            'event_type' => $logData['event_type'],
            'resource_type' => $logData['resource_type'],
            'resource_id' => $logData['resource_id'],
            'url' => $logData['url'],
            'user_agent' => $logData['user_agent'],
            'ip_address' => $logData['ip_address'],
            'extra' => json_encode($logData['extra']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

