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

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage your personal information.
            </p>
        </div>

        <!-- Profile content -->
        <div class="mt-6 grid gap-4 max-w-2xl">
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70">Name</div>
                <div class="mt-1">John Doe</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70">Email</div>
                <div class="mt-1">john@example.com</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70">Role</div>
                <div class="mt-1">Administrator</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70">Member Since</div>
                <div class="mt-1">January 2024</div>
            </div>
        </div>
    </div>
</x-app-layout>
