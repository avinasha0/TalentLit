<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Job Openings</h1>
                <a href="/{{ $tenant->slug }}/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Back to Dashboard
                </a>
            </div>

            @if(count($jobs) > 0)
                <div class="grid gap-6">
                    @foreach($jobs as $job)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $job->title }}</h2>
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
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
                                            {{ $job->globalLocation?->name ?? $job->city?->formatted_location ?? 'No location specified' }}
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">{{ $job->openings_count }}</div>
                                    <div class="text-sm text-gray-600">Openings</div>
                                </div>
                            </div>
                            
                            @if($job->description)
                                <p class="text-gray-700 mb-4">{{ Str::limit($job->description, 200) }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    Published: {{ \Carbon\Carbon::parse($job->published_at)->format('M d, Y') }}
                                </div>
                                <div class="flex space-x-2">
                                    <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                                        View Details
                                    </button>
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                        Apply Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No job openings</h3>
                    <p class="mt-1 text-sm text-gray-500">There are currently no published job openings.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
