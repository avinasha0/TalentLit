
<header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur border-b border-gray-200 dark:border-gray-700">
  <div class="h-16 flex items-center justify-between px-3 lg:px-4">
    <div class="flex items-center gap-2">
      
      <button type="button" data-mobile-toggle
              class="lg:hidden inline-flex items-center justify-center rounded-md p-2 hover:bg-gray-100 dark:hover:bg-gray-700"
              aria-label="Open sidebar">
        <svg class="h-6 w-6 text-gray-700 dark:text-gray-300" viewBox="0 0 24 24" stroke="currentColor" fill="none">
          <path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      
      <a href="<?php echo e(route('home')); ?>" class="font-semibold text-gray-900 dark:text-white">TalentLit</a>
    </div>

    <div class="flex items-center gap-2">
      
      <?php if(isset($tenant) && $tenant->activeSubscription): ?>
        <?php if (app('App\Support\CustomPermissionChecker')->check('manage_users', $tenant)): ?>
        <?php
          $plan = $tenant->activeSubscription->plan;
          $planColors = [
            'free' => 'bg-gray-100 text-gray-800 border-gray-200',
            'pro' => 'bg-blue-100 text-blue-800 border-blue-200',
            'enterprise' => 'bg-purple-100 text-purple-800 border-purple-200'
          ];
          $planColor = $planColors[$plan->slug] ?? 'bg-gray-100 text-gray-800 border-gray-200';
        ?>
        <div class="hidden sm:flex items-center px-3 py-1 rounded-full text-xs font-medium border <?php echo e($planColor); ?>">
          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" clip-rule="evenodd"></path>
          </svg>
          <?php echo e($plan->name); ?>

          <?php if($plan->isFree()): ?>
            <span class="ml-1 text-green-600">Free</span>
          <?php else: ?>
            <span class="ml-1"><?php echo subscriptionPrice($plan->price, $plan->currency); ?></span>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>

      
      <div class="hidden md:flex">
        <input type="search" placeholder="Search…" class="rounded-md text-sm px-3 py-2 border
               bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 w-64">
      </div>

      
      <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open"
                type="button"
                class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                aria-haspopup="menu" :aria-expanded="open">
          <span class="hidden sm:inline text-gray-700 dark:text-gray-300">Account</span>
          <div class="inline-flex h-7 w-7 rounded-full bg-primary-600 text-white items-center justify-center text-xs font-medium">
            <?php echo e(substr(Auth::user()->name ?? 'A', 0, 1)); ?>

          </div>
          <svg class="h-4 w-4 opacity-70 transition-transform duration-200 text-gray-600 dark:text-gray-400" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
            <path d="M5.25 7.5L10 12.25 14.75 7.5"/>
          </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-48 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg py-1 z-[60]"
             role="menu"
             style="display: none;">
          <a href="<?php echo e(isset($tenant) ? route('account.profile', $tenant->slug) : route('dashboard')); ?>"
             class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
             role="menuitem">Profile</a>
          <a href="<?php echo e(isset($tenant) ? route('account.settings', $tenant->slug) : route('dashboard')); ?>"
             class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
             role="menuitem">Settings</a>
          <div class="border-t border-gray-200 dark:border-gray-700 mt-1"></div>
          <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
            <?php echo csrf_field(); ?>
            <button type="submit" 
                    class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" 
                    role="menuitem"
                    onclick="return confirm('Are you sure you want to sign out?')">
              Sign out
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\components\navbar.blade.php ENDPATH**/ ?>