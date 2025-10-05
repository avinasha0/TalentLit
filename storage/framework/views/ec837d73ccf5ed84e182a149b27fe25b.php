
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-gray-200">
  <div class="h-16 flex items-center justify-between px-3 lg:px-4">
    <div class="flex items-center gap-2">
      
      <a href="<?php echo e(route('home')); ?>" class="font-semibold text-xl text-gray-900">
        TalentLit
      </a>
    </div>

    <div class="flex items-center gap-2">
      
      <div class="hidden md:flex">
        <input type="search" placeholder="Search…" class="rounded-md text-sm px-3 py-2 border
               bg-white border-gray-200 w-64">
      </div>

      
      <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(url(($tenantSlug ?? 'acme').'/dashboard')); ?>" 
           class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
          <span class="hidden sm:inline">Dashboard</span>
          <span class="inline-flex h-7 w-7 rounded-full bg-gray-300"></span>
        </a>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" 
           class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
          Sign In
        </a>
        <?php if(Route::has('register')): ?>
          <a href="<?php echo e(route('register')); ?>" 
             class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm bg-primary-600 text-white hover:bg-primary-700">
            Sign Up
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\components\public-navbar.blade.php ENDPATH**/ ?>