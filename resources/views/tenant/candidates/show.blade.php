<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $candidate->full_name }} - {{ tenant()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                            <a href="{{ route('tenant.candidates.index', ['tenant' => tenant()->slug]) }}" class="hover:text-gray-700">Candidates</a>
                            <span>></span>
                            <span class="text-gray-900">{{ $candidate->full_name }}</span>
                        </nav>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $candidate->full_name }}</h1>
                    </div>
                    <div>
                        <a href="{{ route('tenant.candidates.index', ['tenant' => tenant()->slug]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Back to Candidates
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Candidate Details -->
                <div class="lg:col-span-2">
                    <!-- Overview Tab -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-8 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-2xl font-bold text-blue-600">
                                            {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">{{ $candidate->full_name }}</h2>
                                        <p class="text-gray-600">{{ $candidate->primary_email }}</p>
                                        @if($candidate->primary_phone)
                                            <p class="text-sm text-gray-500">{{ $candidate->primary_phone }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($candidate->source)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $candidate->source }}
                                    </span>
                                @endif
                            </div>

                            @if($candidate->tags->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($candidate->tags as $tag)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="px-6 py-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->primary_email }}</dd>
                                </div>
                                @if($candidate->primary_phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                        <dd class="text-sm text-gray-900">{{ $candidate->primary_phone }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->source ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Added</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Applications -->
                    @if($candidate->applications->count() > 0)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                            <div class="px-6 py-8 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Applications ({{ $candidate->applications->count() }})</h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($candidate->applications as $application)
                                    <div class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <a href="{{ route('tenant.jobs.show', ['tenant' => tenant()->slug, 'job' => $application->jobOpening->id]) }}" 
                                                       class="hover:text-blue-600">
                                                        {{ $application->jobOpening->title }}
                                                    </a>
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>{{ $application->jobOpening->department->name }}</span>
                                                    <span>{{ $application->jobOpening->location->name }}</span>
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                        {{ ucfirst(str_replace('_', ' ', $application->jobOpening->employment_type)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-500">
                                                    Applied {{ $application->applied_at->format('M j, Y') }}
                                                </div>
                                                @if($application->currentStage)
                                                    <div class="text-sm font-medium text-blue-600 mt-1">
                                                        {{ $application->currentStage->name }}
                                                    </div>
                                                @endif
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-2
                                                    @if($application->status === 'active') bg-green-100 text-green-800
                                                    @elseif($application->status === 'withdrawn') bg-yellow-100 text-yellow-800
                                                    @elseif($application->status === 'hired') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Applications</span>
                                <span class="text-sm font-medium text-gray-900">{{ $candidate->applications->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Resumes</span>
                                <span class="text-sm font-medium text-gray-900">{{ $candidate->resumes->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Tags</span>
                                <span class="text-sm font-medium text-gray-900">{{ $candidate->tags->count() }}</span>
                            </div>
                        </dl>
                    </div>

                    <!-- Resumes -->
                    @if($candidate->resumes->count() > 0)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumes</h3>
                            <div class="space-y-3">
                                @foreach($candidate->resumes as $resume)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $resume->filename }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ number_format($resume->size / 1024, 1) }} KB • {{ $resume->mime }}
                                            </div>
                                        </div>
                                        <a href="{{ Storage::disk($resume->disk)->url($resume->path) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                Create Application
                            </button>
                            <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                Add Note
                            </button>
                            <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                Add Tag
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
