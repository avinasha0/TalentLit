<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --brand: {{ $branding->primary_color ?? '#4f46e5' }};
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Branding Banner -->
        @if($branding)
            <div class="bg-white border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        @if($branding->logo_path)
                            <img src="{{ Storage::url($branding->logo_path) }}" 
                                 alt="{{ $tenant->name }} Logo" 
                                 class="h-8">
                        @else
                            <h2 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h2>
                        @endif
                        <div class="w-2 h-2 rounded-full" style="background-color: var(--brand);"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                            <a href="{{ route('careers.index', ['tenant' => $tenant->slug]) }}" class="hover:text-gray-700">Careers</a>
                            <span>></span>
                            <span class="text-gray-900">{{ $job->title }}</span>
                        </nav>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                    </div>
                    <div>
                        <a href="{{ route('careers.index', ['tenant' => $tenant->slug]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Back to Jobs
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Job Header -->
                <div class="px-6 py-8 border-b border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
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
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                </span>
                            </div>
                            <div class="text-lg text-gray-600">
                                {{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }} available
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0">
                            <a href="{{ route('careers.apply.create', ['tenant' => tenant()->slug, 'job' => $job->slug]) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="px-6 py-8">
                    @if($job->description)
                        <div class="prose max-w-none">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    @else
                        <p class="text-gray-600">No job description available.</p>
                    @endif
                </div>

                <!-- Job Stages (if any) -->
                @if($job->jobStages->count() > 0)
                    <div class="px-6 py-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Process</h3>
                        <div class="space-y-3">
                            @foreach($job->jobStages->sortBy('sort_order') as $stage)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ $stage->sort_order }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $stage->name }}</h4>
                                        @if($stage->is_terminal)
                                            <p class="text-xs text-gray-600">Final stage</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Apply Button (Bottom) -->
                <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                    <div class="text-center">
                        <a href="{{ route('careers.apply.create', ['tenant' => tenant()->slug, 'job' => $job->slug]) }}" 
                           class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Apply for this Position
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
