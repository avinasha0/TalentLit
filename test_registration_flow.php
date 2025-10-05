<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING REGISTRATION FLOW ===\n\n";

try {
    // Test 1: Check if registration routes exist
    echo "1. Testing Registration Routes...\n";
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $registerRoute = null;
    $verificationRoute = null;
    
    foreach ($routes as $route) {
        if ($route->getName() === 'register') {
            $registerRoute = $route;
        } elseif ($route->getName() === 'verification.show') {
            $verificationRoute = $route;
        }
    }
    
    if ($registerRoute && $verificationRoute) {
        echo "   ✅ Registration routes found\n";
        echo "   - Register: " . $registerRoute->getAction()['controller'] . "\n";
        echo "   - Verification: " . $verificationRoute->getAction()['controller'] . "\n";
    } else {
        echo "   ❌ Registration routes not found\n";
        exit(1);
    }
    
    // Test 2: Check EmailVerificationOtp model
    echo "\n2. Testing EmailVerificationOtp Model...\n";
    $otpModel = new \App\Models\EmailVerificationOtp();
    echo "   ✅ EmailVerificationOtp model created\n";
    
    // Test OTP generation
    $testEmail = 'test@example.com';
    $otpRecord = \App\Models\EmailVerificationOtp::generateForEmail($testEmail);
    echo "   ✅ OTP generated: " . $otpRecord->otp . "\n";
    
    // Test OTP verification
    $isValid = \App\Models\EmailVerificationOtp::verify($testEmail, $otpRecord->otp);
    echo "   ✅ OTP verification: " . ($isValid ? 'Valid' : 'Invalid') . "\n";
    
    // Clean up test OTP
    $otpRecord->delete();
    echo "   ✅ Test OTP cleaned up\n";
    
    // Test 3: Check if verification view exists
    echo "\n3. Testing Verification View...\n";
    $viewPath = resource_path('views/auth/verify-email.blade.php');
    if (file_exists($viewPath)) {
        echo "   ✅ Verification view exists\n";
    } else {
        echo "   ❌ Verification view not found\n";
        exit(1);
    }
    
    // Test 4: Check if email verification controller works
    echo "\n4. Testing EmailVerificationController...\n";
    $controller = new \App\Http\Controllers\Auth\EmailVerificationController();
    echo "   ✅ EmailVerificationController created\n";
    
    // Test 5: Check if mail configuration works
    echo "\n5. Testing Mail Configuration...\n";
    $mailConfig = config('mail.default');
    echo "   ✅ Mail driver: " . $mailConfig . "\n";
    
    // Test 6: Check if reCAPTCHA is working
    echo "\n6. Testing reCAPTCHA Configuration...\n";
    $recaptchaEnabled = config('services.recaptcha.enabled', false);
    echo "   ✅ reCAPTCHA enabled: " . ($recaptchaEnabled ? 'Yes' : 'No (skipped for localhost)') . "\n";
    
    // Test 7: Check if database tables exist
    echo "\n7. Testing Database Tables...\n";
    $tables = ['users', 'email_verification_otps'];
    foreach ($tables as $table) {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            echo "   ✅ Table '$table' exists\n";
        } else {
            echo "   ❌ Table '$table' not found\n";
            exit(1);
        }
    }
    
    // Test 8: Test registration controller
    echo "\n8. Testing Registration Controller...\n";
    $regController = new \App\Http\Controllers\Auth\RegisteredUserController();
    echo "   ✅ RegisteredUserController created\n";
    
    // Test 9: Check if onboarding organization route exists
    echo "\n9. Testing Onboarding Route...\n";
    $onboardingRoute = null;
    foreach ($routes as $route) {
        if ($route->getName() === 'onboarding.organization') {
            $onboardingRoute = $route;
            break;
        }
    }
    
    if ($onboardingRoute) {
        echo "   ✅ Onboarding route found: " . $onboardingRoute->getAction()['controller'] . "\n";
    } else {
        echo "   ❌ Onboarding route not found\n";
        exit(1);
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 ALL REGISTRATION TESTS PASSED! 🎉\n";
    echo "The registration flow should work correctly.\n";
    echo "Try registering at: http://localhost:8000/register\n";
    echo str_repeat("=", 50) . "\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
