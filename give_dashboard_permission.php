<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Bootstrap Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__ . '/routes/web.php',
        api: __DIR__ . '/routes/api.php',
        commands: __DIR__ . '/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "Give Dashboard Permission to All Users\n";
echo "=====================================\n\n";

try {
    // Step 1: Ensure permission exists
    $permission = Permission::firstOrCreate([
        'name' => 'view dashboard',
        'guard_name' => 'web'
    ]);
    echo "✅ 'view dashboard' permission exists\n";

    // Step 2: Give permission to all users
    $users = User::all();
    echo "Found {$users->count()} users\n\n";
    
    foreach ($users as $user) {
        echo "User: {$user->name}\n";
        
        if (!$user->hasPermissionTo('view dashboard')) {
            $user->givePermissionTo('view dashboard');
            echo "  ✅ Added 'view dashboard' permission\n";
        } else {
            echo "  ✅ Already has 'view dashboard' permission\n";
        }
    }

    echo "\n🎉 All users now have 'view dashboard' permission!\n";
    echo "Test at: https://talentlit.com/nine/dashboard\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
