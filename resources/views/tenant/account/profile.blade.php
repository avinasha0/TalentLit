<x-app-layout :tenant="$tenant ?? null">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug ?? 'acme')],
                ['label' => 'Profile', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Profile</h1>
            <p class="mt-1 text-sm text-white">
                Manage your personal information and account settings.
            </p>
        </div>

        <!-- Success Messages -->
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profile Information Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
            
            <!-- Basic Info Display -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-sm text-gray-900">{{ $user->name }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-sm text-gray-900">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Unverified
                        </span>
                    @endif
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-500">Role</label>
                    <p class="text-sm text-gray-900">{{ $roleName }}</p>
                </div>
                <div class="space-y-1">
                    <label class="text-sm font-medium text-gray-500">Member Since</label>
                    <p class="text-sm text-gray-900">{{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="border-t pt-6">
                <h3 class="text-md font-medium text-gray-900 mb-4">Edit Profile</h3>
                <form method="POST" action="{{ route('account.profile.update', $tenant->slug) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>
            
            <button onclick="togglePasswordForm()" class="text-sm text-blue-600 hover:text-blue-800 mb-4">
                Change password
            </button>
            
            <div id="password-form" class="hidden">
                <form method="POST" action="{{ route('account.profile.password', $tenant->slug) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('current_password') border-red-300 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Update Password
                        </button>
                        <button type="button" onclick="togglePasswordForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Email Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Update Email Address</h2>
            
            <button onclick="toggleEmailForm()" class="text-sm text-blue-600 hover:text-blue-800 mb-4">
                Update email address
            </button>
            
            <div id="email-form" class="hidden">
                <form method="POST" action="{{ route('account.profile.email', $tenant->slug) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="new_email" class="block text-sm font-medium text-gray-700">New Email Address</label>
                            <input type="email" name="email" id="new_email" value="{{ old('email', $user->email) }}" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="password" id="email_password" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Update Email
                        </button>
                        <button type="button" onclick="toggleEmailForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Preferences Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Notification Preferences</h2>
            
            <form method="POST" action="{{ route('account.profile.notifications', $tenant->slug) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="email_weekly_summaries" value="1" class="rounded" {{ $user->email_weekly_summaries ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Email me weekly summaries</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="notify_new_applications" value="1" class="rounded" {{ $user->notify_new_applications ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Notify me of new applications</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="send_marketing_emails" value="1" class="rounded" {{ $user->send_marketing_emails ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Send marketing emails</span>
                    </label>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Save Notification Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById('password-form');
            form.classList.toggle('hidden');
        }

        function toggleEmailForm() {
            const form = document.getElementById('email-form');
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
