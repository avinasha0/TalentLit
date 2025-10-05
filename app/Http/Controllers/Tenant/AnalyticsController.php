<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        
        // Check permission using our custom system
        if (!app('App\\Support\\CustomPermissionChecker')->check('view_analytics', $tenant)) {
            abort(403, 'Unauthorized');
        }
        
        return view('tenant.analytics.index', compact('tenant'));
    }

    public function data(Request $request)
    {
        $tenant = tenant();
        
        // Check permission using our custom system
        if (!app('App\\Support\\CustomPermissionChecker')->check('view_analytics', $tenant)) {
            abort(403, 'Unauthorized');
        }
        
        $tenantId = tenant_id();
        $from = $request->get('from', now()->subDays(90)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        
        // Cache key includes tenant and date range
        $cacheKey = "analytics_data_{$tenantId}_{$from}_{$to}";
        
        return Cache::remember($cacheKey, 60, function () use ($tenantId, $from, $to) {
            return $this->getAnalyticsData($tenantId, $from, $to);
        });
    }

    public function export(Request $request)
    {
        $tenant = tenant();
        
        // Check permission using our custom system
        if (!app('App\\Support\\CustomPermissionChecker')->check('view_analytics', $tenant)) {
            abort(403, 'Unauthorized');
        }
        
        $tenantId = tenant_id();
        $from = $request->get('from', now()->subDays(90)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        
        $data = $this->getAnalyticsData($tenantId, $from, $to);
        
        $csvData = $this->formatDataForCsv($data);
        
        $filename = "analytics_export_{$from}_to_{$to}.csv";
        
        return response()->streamDownload(function () use ($csvData) {
            $handle = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getAnalyticsData($tenantId, $from, $to)
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();
        $isShortRange = $fromDate->diffInDays($toDate) <= 31;
        
        return [
            'applications_over_time' => $this->getApplicationsOverTime($tenantId, $fromDate, $toDate, $isShortRange),
            'hires_over_time' => $this->getHiresOverTime($tenantId, $fromDate, $toDate, $isShortRange),
            'stage_funnel' => $this->getStageFunnel($tenantId, $fromDate, $toDate),
            'time_to_hire_summary' => $this->getTimeToHireSummary($tenantId, $fromDate, $toDate),
            'source_effectiveness' => $this->getSourceEffectiveness($tenantId, $fromDate, $toDate),
            'open_jobs_by_dept' => $this->getOpenJobsByDepartment($tenantId),
            'pipeline_snapshot' => $this->getPipelineSnapshot($tenantId),
        ];
    }

    private function getApplicationsOverTime($tenantId, $fromDate, $toDate, $isShortRange)
    {
        if ($isShortRange) {
            return DB::table('applications')
                ->select(DB::raw('DATE(applied_at) as date'), DB::raw('COUNT(*) as applications_count'))
                ->where('tenant_id', $tenantId)
                ->whereBetween('applied_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('DATE(applied_at)'))
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'applications_count' => (int) $item->applications_count
                    ];
                })
                ->toArray();
        } else {
            // For longer ranges, group by week
            return DB::table('applications')
                ->select(DB::raw('YEARWEEK(applied_at) as date'), DB::raw('COUNT(*) as applications_count'))
                ->where('tenant_id', $tenantId)
                ->whereBetween('applied_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('YEARWEEK(applied_at)'))
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'applications_count' => (int) $item->applications_count
                    ];
                })
                ->toArray();
        }
    }

    private function getHiresOverTime($tenantId, $fromDate, $toDate, $isShortRange)
    {
        if ($isShortRange) {
            return DB::table('applications')
                ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
                ->select(DB::raw('DATE(applied_at) as date'), DB::raw('COUNT(*) as hires_count'))
                ->where('applications.tenant_id', $tenantId)
                ->where('job_stages.name', 'like', '%Hired%')
                ->whereBetween('applications.applied_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('DATE(applied_at)'))
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'hires_count' => (int) $item->hires_count
                    ];
                })
                ->toArray();
        } else {
            // For longer ranges, group by week
            return DB::table('applications')
                ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
                ->select(DB::raw('YEARWEEK(applied_at) as date'), DB::raw('COUNT(*) as hires_count'))
                ->where('applications.tenant_id', $tenantId)
                ->where('job_stages.name', 'like', '%Hired%')
                ->whereBetween('applications.applied_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('YEARWEEK(applied_at)'))
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'hires_count' => (int) $item->hires_count
                    ];
                })
                ->toArray();
        }
    }

    private function getStageFunnel($tenantId, $fromDate, $toDate)
    {
        return DB::table('applications')
            ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
            ->select('job_stages.name as stage', DB::raw('COUNT(*) as applications_count'))
            ->where('applications.tenant_id', $tenantId)
            ->whereBetween('applications.applied_at', [$fromDate, $toDate])
            ->groupBy('job_stages.id', 'job_stages.name', 'job_stages.sort_order')
            ->orderBy('job_stages.sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'stage' => $item->stage,
                    'applications_count' => (int) $item->applications_count
                ];
            })
            ->toArray();
    }

    private function getTimeToHireSummary($tenantId, $fromDate, $toDate)
    {
        $hiredApplications = DB::table('applications')
            ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
            ->select('applications.applied_at', 'applications.updated_at')
            ->where('applications.tenant_id', $tenantId)
            ->where('job_stages.name', 'like', '%Hired%')
            ->whereBetween('applications.applied_at', [$fromDate, $toDate])
            ->whereNotNull('applications.applied_at')
            ->get();

        if ($hiredApplications->isEmpty()) {
            return [
                'median_days' => 0,
                'p75_days' => 0,
                'p90_days' => 0,
                'sample_size' => 0
            ];
        }

        $durations = $hiredApplications->map(function ($app) {
            return Carbon::parse($app->applied_at)->diffInDays(Carbon::parse($app->updated_at));
        })->sort()->values();

        $count = $durations->count();
        $median = $durations->get(floor($count * 0.5));
        $p75 = $durations->get(floor($count * 0.75));
        $p90 = $durations->get(floor($count * 0.9));

        return [
            'median_days' => $median ?? 0,
            'p75_days' => $p75 ?? 0,
            'p90_days' => $p90 ?? 0,
            'sample_size' => $count
        ];
    }

    private function getSourceEffectiveness($tenantId, $fromDate, $toDate)
    {
        // Check if source column exists on candidates table
        $hasSourceColumn = DB::getSchemaBuilder()->hasColumn('candidates', 'source');
        
        if (!$hasSourceColumn) {
            return [
                ['source' => 'Unknown', 'applications' => 0, 'hired' => 0]
            ];
        }

        $sourceData = DB::table('applications')
            ->join('candidates', 'applications.candidate_id', '=', 'candidates.id')
            ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
            ->select(
                'candidates.source',
                DB::raw('COUNT(*) as applications'),
                DB::raw('SUM(CASE WHEN job_stages.name LIKE "%Hired%" THEN 1 ELSE 0 END) as hired')
            )
            ->where('applications.tenant_id', $tenantId)
            ->whereBetween('applications.applied_at', [$fromDate, $toDate])
            ->whereNotNull('candidates.source')
            ->groupBy('candidates.source')
            ->orderBy('applications', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'source' => $item->source ?: 'Unknown',
                    'applications' => (int) $item->applications,
                    'hired' => (int) $item->hired
                ];
            })
            ->toArray();

        return empty($sourceData) ? [['source' => 'Unknown', 'applications' => 0, 'hired' => 0]] : $sourceData;
    }

    private function getOpenJobsByDepartment($tenantId)
    {
        return DB::table('job_openings')
            ->join('departments', 'job_openings.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('COUNT(*) as open_jobs'))
            ->where('job_openings.tenant_id', $tenantId)
            ->whereIn('job_openings.status', ['draft', 'published'])
            ->groupBy('departments.id', 'departments.name')
            ->orderBy('open_jobs', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'department' => $item->department,
                    'open_jobs' => (int) $item->open_jobs
                ];
            })
            ->toArray();
    }

    private function getPipelineSnapshot($tenantId)
    {
        return DB::table('applications')
            ->join('job_stages', 'applications.current_stage_id', '=', 'job_stages.id')
            ->select('job_stages.name as stage', DB::raw('COUNT(*) as in_stage_count'))
            ->where('applications.tenant_id', $tenantId)
            ->whereIn('applications.status', ['applied', 'screening', 'interviewing', 'offered'])
            ->groupBy('job_stages.id', 'job_stages.name', 'job_stages.sort_order')
            ->orderBy('job_stages.sort_order')
            ->get()
            ->map(function ($item) {
                return [
                    'stage' => $item->stage,
                    'in_stage_count' => (int) $item->in_stage_count
                ];
            })
            ->toArray();
    }

    private function formatDataForCsv($data)
    {
        $csvData = [];
        
        // Applications over time
        $csvData[] = ['Applications Over Time'];
        $csvData[] = ['Date', 'Applications Count'];
        foreach ($data['applications_over_time'] as $item) {
            $csvData[] = [$item['date'], $item['applications_count']];
        }
        
        $csvData[] = []; // Empty row
        
        // Stage funnel
        $csvData[] = ['Stage Funnel'];
        $csvData[] = ['Stage', 'Applications Count'];
        foreach ($data['stage_funnel'] as $item) {
            $csvData[] = [$item['stage'], $item['applications_count']];
        }
        
        return $csvData;
    }
}
