{{-- resources/views/components/sidebar.blade.php --}}
@props(['tenantSlug' => 'acme'])

{{-- Backdrop (mobile only, below navbar) --}}
<div id="app-sidebar-backdrop"
     class="fixed top-16 left-0 right-0 bottom-0 z-30 bg-black/40 hidden lg:hidden"
     aria-hidden="true"></div>

<aside id="app-sidebar"
  class="fixed top-16 left-0 z-40 h-[calc(100vh-4rem)] w-64 transform -translate-x-full transition-transform duration-300 ease-in-out
         bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800
         lg:translate-x-0 lg:w-64"
  role="navigation" aria-label="Sidebar">

  <div class="flex items-center h-14 px-4 gap-2" data-role="center-when-collapsed">
    <button type="button" title="Collapse sidebar" aria-label="Collapse sidebar"
            class="hidden lg:inline-flex items-center justify-center rounded-md p-2 hover:bg-gray-100 dark:hover:bg-gray-800"
            data-action="collapse-sidebar">
      {{-- icon --}}
      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 5h14M3 10h10M3 15h14"/></svg>
    </button>
    <span class="font-semibold truncate" data-role="brand-text">HireHub</span>
  </div>

  <nav class="mt-2 px-2 space-y-1">
    <a href="{{ url($tenantSlug.'/dashboard') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm
              hover:bg-gray-100 dark:hover:bg-gray-800">
      <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
      <span class="truncate" data-role="brand-text">Dashboard</span>
    </a>
    <a href="{{ url($tenantSlug.'/jobs') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm
              hover:bg-gray-100 dark:hover:bg-gray-800">
      <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 7h16v12H4zM8 7V5h8v2"/></svg>
      <span class="truncate" data-role="brand-text">Jobs</span>
    </a>
    <a href="{{ url($tenantSlug.'/candidates') }}"
       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm
              hover:bg-gray-100 dark:hover:bg-gray-800">
      <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/></svg>
      <span class="truncate" data-role="brand-text">Candidates</span>
    </a>
  </nav>
</aside>