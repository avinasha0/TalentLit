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
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="mt-1 text-sm text-gray-500">
                Application preferences and configuration.
            </p>
        </div>

        <!-- Settings content -->
        <div class="mt-6 grid gap-4 max-w-2xl">
            <div class="rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium mb-3">Notifications</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" class="rounded" checked>
                        <span class="text-sm">Email me weekly summaries</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" class="rounded" checked>
                        <span class="text-sm">Notify me of new applications</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" class="rounded">
                        <span class="text-sm">Send marketing emails</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium mb-3">Appearance</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <input type="radio" name="theme" value="light" class="rounded" checked>
                        <span class="text-sm">Light mode</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="radio" name="theme" value="dark" class="rounded">
                        <span class="text-sm">Dark mode</span>
                    </label>
                    <label class="flex items-center gap-3">
                        <input type="radio" name="theme" value="auto" class="rounded">
                        <span class="text-sm">System preference</span>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 p-4">
                <h3 class="text-sm font-medium mb-3">Account</h3>
                <div class="space-y-3">
                    <button class="text-sm text-blue-600 hover:text-blue-800">
                        Change password
                    </button>
                    <button class="text-sm text-blue-600 hover:text-blue-800">
                        Update email address
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
