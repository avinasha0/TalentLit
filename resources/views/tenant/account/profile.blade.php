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
            <h1 class="text-2xl font-bold text-white">Profile</h1>
            <p class="mt-1 text-sm text-white">
                Manage your personal information.
            </p>
        </div>

        <!-- Profile content -->
        <div class="mt-6 grid gap-4 max-w-2xl">
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70 text-white">Name</div>
                <div class="mt-1 text-white">John Doe</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70 text-white">Email</div>
                <div class="mt-1 text-white">john@example.com</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70 text-white">Role</div>
                <div class="mt-1 text-white">Administrator</div>
            </div>
            <div class="rounded-lg border border-gray-200 p-4">
                <div class="text-sm opacity-70 text-white">Member Since</div>
                <div class="mt-1 text-white">January 2024</div>
            </div>
        </div>
    </div>
</x-app-layout>
