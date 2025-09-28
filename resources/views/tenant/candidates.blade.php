<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Candidates</h1>
                <div class="flex space-x-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Add Candidate
                    </button>
                    <a href="/{{ $tenant->slug }}/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            @if(count($candidates) > 0)
                <div class="mb-4">
                    <div class="flex items-center space-x-4">
                        <input type="text" placeholder="Search candidates..." class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Sources</option>
                            <option value="LinkedIn">LinkedIn</option>
                            <option value="Naukri">Naukri</option>
                            <option value="Indeed">Indeed</option>
                            <option value="Referral">Referral</option>
                            <option value="Company Website">Company Website</option>
                            <option value="Job Fair">Job Fair</option>
                        </select>
                        <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Tags</option>
                            <option value="PHP">PHP</option>
                            <option value="Laravel">Laravel</option>
                            <option value="Immediate Joiner">Immediate Joiner</option>
                            <option value="Senior Level">Senior Level</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4">
                    @foreach($candidates as $candidate)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $candidate->name }}</h3>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                            {{ $candidate->source }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $candidate->email }}
                                        </span>
                                        @if($candidate->phone)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $candidate->phone }}
                                            </span>
                                        @endif
                                    </div>

                                    @if(count($candidate->tags) > 0)
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($candidate->tags as $tag)
                                                <span class="px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="text-xs text-gray-500">
                                        Added: {{ \Carbon\Carbon::parse($candidate->created_at)->format('M d, Y') }}
                                    </div>
                                </div>

                                <div class="flex space-x-2 ml-4">
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm">
                                        View Profile
                                    </button>
                                    <button class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 text-sm">
                                        Create Application
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Showing {{ count($candidates) }} candidates
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Previous</button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Next</button>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No candidates</h3>
                    <p class="mt-1 text-sm text-gray-500">There are currently no candidates in the system.</p>
                    <div class="mt-6">
                        <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Add First Candidate
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
