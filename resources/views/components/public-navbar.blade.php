{{-- resources/views/components/public-navbar.blade.php --}}
<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
  <div class="h-16 flex items-center justify-between px-3 lg:px-4">
    <div class="flex items-center gap-2">
      {{-- Brand --}}
      <a href="{{ route('home') }}" class="font-semibold text-xl text-gray-900 dark:text-white">
        HireHub
      </a>
    </div>

    <div class="flex items-center gap-2">
      {{-- (Optional) Search placeholder --}}
      <div class="hidden md:flex">
        <input type="search" placeholder="Search…" class="rounded-md text-sm px-3 py-2 border
               bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 w-64">
      </div>

      {{-- Auth buttons --}}
      @auth
        <a href="{{ url(($tenantSlug ?? 'acme').'/dashboard') }}" 
           class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
          <span class="hidden sm:inline">Dashboard</span>
          <span class="inline-flex h-7 w-7 rounded-full bg-gray-300 dark:bg-gray-700"></span>
        </a>
      @else
        <a href="{{ route('login') }}" 
           class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
          Sign In
        </a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" 
             class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm bg-primary-600 text-white hover:bg-primary-700">
            Sign Up
          </a>
        @endif
      @endauth
    </div>
  </div>
</header>
