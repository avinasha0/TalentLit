<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - {{ tenant()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ tenant()->name }}</h1>
                        <p class="text-gray-600">Join our team</p>
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

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="employment_type" 
                                id="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}" {{ request('employment_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
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

        <!-- Jobs List -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Open Positions</h2>
                <p class="text-gray-600">{{ $jobs->total() }} job{{ $jobs->total() !== 1 ? 's' : '' }} found</p>
            </div>

            @if($jobs->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($jobs as $job)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
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
                                    </div>
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                    </span>
                                </div>
                            </div>

                            @if($job->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit(strip_tags($job->description), 150) }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    {{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }}
                                </div>
                                <a href="{{ route('careers.show', ['tenant' => tenant()->slug, 'job' => $job->slug]) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
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
        </main>
    </div>
</body>
</html>
