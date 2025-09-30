{{-- resources/views/components/sidebar.blade.php --}}
@props(['tenant' => null])

{{-- Backdrop (mobile only, below navbar) --}}
<div x-show="$store.sidebar.mobileOpen"
     @click="$store.sidebar.closeMobile()"
     class="fixed top-16 left-0 right-0 bottom-0 z-30 bg-black/40 lg:hidden"
     aria-hidden="true"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"></div>

<aside x-data="{}"
       :class="{ 'mobile-open': $store.sidebar.mobileOpen, 'collapsed': $store.sidebar.collapsed }"
       class="fixed top-0 left-0 z-40 h-full w-64 transform -translate-x-full transition-transform duration-300 ease-in-out
              bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700
              md:translate-x-0"
       role="navigation" aria-label="Sidebar">

  <div class="flex items-center h-14 px-4 gap-2">
    <button type="button" 
            title="Collapse sidebar" 
            aria-label="Collapse sidebar"
            class="hidden lg:inline-flex items-center justify-center rounded-md p-2 hover:bg-gray-100 dark:hover:bg-gray-700"
            @click="$store.sidebar.toggle()">
      <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
        <path d="M3 5h14M3 10h10M3 15h14"/>
      </svg>
    </button>
    <span class="font-semibold truncate text-gray-900 dark:text-white">HireHub</span>
  </div>

  <nav class="mt-2 px-2 space-y-1">
    <a href="{{ route('tenant.dashboard', $tenant->slug) }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300
              hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('tenant.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
      <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/>
      </svg>
      <span class="truncate">Dashboard</span>
    </a>
    <a href="{{ route('tenant.jobs.index', $tenant->slug) }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300
              hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('tenant.jobs.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
      <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path d="M4 7h16v12H4zM8 7V5h8v2"/>
      </svg>
      <span class="truncate">Jobs</span>
    </a>
    <a href="{{ route('tenant.candidates.index', $tenant->slug) }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-700 dark:text-gray-300
              hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('tenant.candidates.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
      <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/>
      </svg>
      <span class="truncate">Candidates</span>
    </a>
  </nav>
</aside>