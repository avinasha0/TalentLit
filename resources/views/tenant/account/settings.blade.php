<x-app-layout :tenant="$tenant ?? null">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug ?? 'acme')],
                ['label' => 'Settings', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Settings</h1>
            <p class="mt-1 text-sm text-white">
                Application preferences and configuration.
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

        <!-- Settings content -->
        <div class="mt-6 grid gap-4 max-w-2xl">
            <!-- Notifications Section -->
            <div class="rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium mb-3 text-white">Notifications</h3>
                <form method="POST" action="{{ route('account.settings.notifications', $tenant->slug) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="email_weekly_summaries" value="1" class="rounded" {{ $user->email_weekly_summaries ? 'checked' : '' }}>
                            <span class="text-sm text-white">Email me weekly summaries</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="notify_new_applications" value="1" class="rounded" {{ $user->notify_new_applications ? 'checked' : '' }}>
                            <span class="text-sm text-white">Notify me of new applications</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="send_marketing_emails" value="1" class="rounded" {{ $user->send_marketing_emails ? 'checked' : '' }}>
                            <span class="text-sm text-white">Send marketing emails</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Save Notification Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Section -->
            <div class="rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium mb-3 text-white">Account</h3>
                <div class="space-y-4">
                    <!-- Change Password -->
                    <div>
                        <button onclick="togglePasswordForm()" class="text-sm text-blue-600 hover:text-blue-800">
                            Change password
                        </button>
                        <div id="password-form" class="hidden mt-3 p-4 bg-gray-50 rounded-md">
                            <form method="POST" action="{{ route('account.settings.password', $tenant->slug) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-3">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
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

                    <!-- Update Email -->
                    <div>
                        <button onclick="toggleEmailForm()" class="text-sm text-blue-600 hover:text-blue-800">
                            Update email address
                        </button>
                        <div id="email-form" class="hidden mt-3 p-4 bg-gray-50 rounded-md">
                            <form method="POST" action="{{ route('account.settings.email', $tenant->slug) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-3">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">New Email Address</label>
                                        <input type="email" name="email" id="email" value="{{ $user->email }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                        <input type="password" name="password" id="email_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
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
                </div>
            </div>
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
