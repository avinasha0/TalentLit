<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} Dashboard - HireHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">HireHub</h1>
                    <span class="ml-2 text-sm text-gray-500">/ {{ $tenant->name }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Welcome, {{ $user->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Welcome to {{ $tenant->name }} Dashboard
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        <!-- Tenant Info Card -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-900">Tenant Information</h3>
                            <div class="mt-2">
                                <p class="text-sm text-blue-700"><strong>Name:</strong> {{ $tenant->name }}</p>
                                <p class="text-sm text-blue-700"><strong>Slug:</strong> {{ $tenant->slug }}</p>
                                <p class="text-sm text-blue-700"><strong>Created:</strong> {{ $tenant->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- User Info Card -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-green-900">User Information</h3>
                            <div class="mt-2">
                                <p class="text-sm text-green-700"><strong>Name:</strong> {{ $user->name }}</p>
                                <p class="text-sm text-green-700"><strong>Email:</strong> {{ $user->email }}</p>
                                <p class="text-sm text-green-700"><strong>Roles:</strong> 
                                    @if($user->roles->count() > 0)
                                        {{ $user->roles->pluck('name')->join(', ') }}
                                    @else
                                        No roles assigned
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-purple-900">Quick Actions</h3>
                            <div class="mt-2 space-y-2">
                                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">
                                    Manage Users
                                </button>
                                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">
                                    View Reports
                                </button>
                                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm">
                                    Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <p class="text-sm">
                            <strong>Success!</strong> You are now logged in to the {{ $tenant->name }} tenant dashboard. 
                            The path-based tenancy is working correctly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
