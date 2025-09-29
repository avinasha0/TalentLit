{{-- resources/views/components/navbar.blade.php --}}
<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
  <div class="h-16 flex items-center justify-between px-3 lg:px-4">
    <div class="flex items-center gap-2">
      {{-- Mobile hamburger --}}
      <button type="button"
              class="lg:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-gray-100 dark:hover:bg-gray-800"
              data-action="toggle-sidebar"
              aria-label="Open sidebar" aria-expanded="false">
        <svg class="h-6 w-6" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      {{-- Brand (visible even without sidebar) --}}
      <a href="{{ route('home') }}" class="font-semibold">HireHub</a>
    </div>

    <div class="flex items-center gap-2">
      {{-- (Optional) Search placeholder --}}
      <div class="hidden md:flex">
        <input type="search" placeholder="Search…" class="rounded-md text-sm px-3 py-2 border
               bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 w-64">
      </div>

      {{-- Account dropdown --}}
      <div class="relative">
        <button id="account-button"
                type="button"
                class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
                data-action="toggle-account-menu"
                aria-haspopup="menu" aria-expanded="false" aria-controls="account-menu">
          <span class="hidden sm:inline">Account</span>
          <span class="inline-flex h-7 w-7 rounded-full bg-gray-300 dark:bg-gray-700"></span>
          <svg class="h-4 w-4 opacity-70" viewBox="0 0 20 20" fill="currentColor"><path d="M5.25 7.5L10 12.25 14.75 7.5"/></svg>
        </button>

        <div id="account-menu"
             class="absolute right-0 mt-2 w-48 rounded-md border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg py-1 hidden z-[60]"
             role="menu" aria-labelledby="account-button">
          <a href="{{ url(($tenantSlug ?? 'acme').'/account/profile') }}"
             class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
             role="menuitem">Profile</a>
          <a href="{{ url(($tenantSlug ?? 'acme').'/account/settings') }}"
             class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
             role="menuitem">Settings</a>
          <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Sign out?')" class="border-t border-gray-200 dark:border-gray-800 mt-1">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800" role="menuitem">
              Sign out
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>