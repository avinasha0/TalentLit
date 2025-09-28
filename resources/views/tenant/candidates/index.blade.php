<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates - {{ tenant()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Candidates</h1>
                        <p class="text-gray-600">Manage candidate profiles and applications</p>
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
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Name or email..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                        <select name="source" 
                                id="source"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Sources</option>
                            @foreach($sources as $source)
                                <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>
                                    {{ $source }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                        <select name="tag" 
                                id="tag"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Tags</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>
                                    {{ $tag }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter Candidates
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Candidates List -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Showing {{ $candidates->total() }} candidates</h2>
            </div>

            @if($candidates->count() > 0)
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($candidates as $candidate)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        <a href="{{ route('tenant.candidates.show', ['tenant' => tenant()->slug, 'candidate' => $candidate->id]) }}" 
                                           class="hover:text-blue-600">
                                            {{ $candidate->full_name }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600">{{ $candidate->primary_email }}</p>
                                    @if($candidate->primary_phone)
                                        <p class="text-sm text-gray-500">{{ $candidate->primary_phone }}</p>
                                    @endif
                                </div>
                                @if($candidate->source)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $candidate->source }}
                                    </span>
                                @endif
                            </div>

                            @if($candidate->tags->count() > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($candidate->tags as $tag)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    {{ $candidate->applications->count() }} application{{ $candidate->applications->count() !== 1 ? 's' : '' }}
                                </div>
                                <a href="{{ route('tenant.candidates.show', ['tenant' => tenant()->slug, 'candidate' => $candidate->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $candidates->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No candidates found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
