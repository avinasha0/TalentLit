<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();
            
        return view('home', compact('plans'));
    }

    /**
     * Display the main features page
     */
    public function features()
    {
        return view('features.index');
    }

    /**
     * Display the candidate sourcing features page
     */
    public function candidateSourcing()
    {
        return view('features.candidate-sourcing');
    }

    /**
     * Display the hiring pipeline features page
     */
    public function hiringPipeline()
    {
        return view('features.hiring-pipeline');
    }

    /**
     * Display the career site features page
     */
    public function careerSite()
    {
        return view('features.career-site');
    }

    /**
     * Display the job advertising features page
     */
    public function jobAdvertising()
    {
        return view('features.job-advertising');
    }

    /**
     * Display the employee referral features page
     */
    public function employeeReferral()
    {
        return view('features.employee-referral');
    }

    /**
     * Display the resume management features page
     */
    public function resumeManagement()
    {
        return view('features.resume-management');
    }

    /**
     * Display the manage submission features page
     */
    public function manageSubmission()
    {
        return view('features.manage-submission');
    }

    /**
     * Display the hiring analytics features page
     */
    public function hiringAnalytics()
    {
        return view('features.hiring-analytics');
    }
}
