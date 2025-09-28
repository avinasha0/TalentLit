<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - {{ tenant()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Job Openings</h1>
                        <p class="text-gray-600">Manage job postings and applications</p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.dashboard', ['tenant' => tenant()->slug]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Filters -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               name="keyword" 
                               id="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Job title or keywords..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" 
                                id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department" 
                                id="department"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <select name="location" 
                                id="location"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter Jobs
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Jobs Table -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $jobs->total() }} job{{ $jobs->total() !== 1 ? 's' : '' }} found
                    </h3>
                </div>

                @if($jobs->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($jobs as $job)
                            <li class="px-4 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 truncate">
                                                    <a href="{{ route('tenant.jobs.show', ['tenant' => tenant()->slug, 'job' => $job->id]) }}" 
                                                       class="hover:text-blue-600">
                                                        {{ $job->title }}
                                                    </a>
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        {{ $job->department->name }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $job->location->name }}
                                                    </span>
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <div class="text-right">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $job->applications->count() }} application{{ $job->applications->count() !== 1 ? 's' : '' }}
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                        @if($job->status === 'published') bg-green-100 text-green-800
                                                        @elseif($job->status === 'draft') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($job->status) }}
                                                    </span>
                                                    @if($job->published_at)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Published {{ $job->published_at->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $jobs->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
