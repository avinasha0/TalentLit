<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help center index page.
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * Display a specific help page.
     */
    public function page($slug)
    {
        $allowedPages = [
            'register', 'login', 'onboarding', 'invite-team', 'dashboard', 'jobs', 
            'careers', 'candidates', 'applications', 'pipeline', 'interviews', 
            'notes-tags', 'analytics', 'settings', 'roles-permissions', 
            'integrations', 'troubleshooting', 'security', 'deploy', 'contact'
        ];

        if (!in_array($slug, $allowedPages)) {
            abort(404);
        }

        return view("help.{$slug}");
    }
}
