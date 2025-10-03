<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verify Your Email
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    We've sent a verification code to
                </p>
                <p class="text-center text-sm font-medium text-blue-600">
                    {{ $email }}
                </p>
            </div>

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('verification.verify-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">
                        Enter Verification Code
                    </label>
                    <div class="mt-1">
                        <input 
                            id="otp" 
                            name="otp" 
                            type="text" 
                            required 
                            maxlength="6"
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm text-center text-2xl font-mono tracking-widest"
                            placeholder="000000"
                            autocomplete="off"
                        >
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Enter the 6-digit code sent to your email address.
                    </p>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Verify Email
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <p class="text-gray-600">
                            Didn't receive the code?
                        </p>
                    </div>
                    <div class="text-sm">
                        <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline"
                            >
                                Resend Code
                            </button>
                        </form>
                    </div>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Need help?</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        If you're having trouble receiving the verification code, please check your spam folder or 
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            try registering again
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on OTP input
        document.getElementById('otp').focus();

        // Auto-submit when 6 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            e.target.value = value;
            
            if (value.length === 6) {
                // Small delay to show the complete code
                setTimeout(() => {
                    e.target.form.submit();
                }, 100);
            }
        });

        // Only allow digits
        document.getElementById('otp').addEventListener('keypress', function(e) {
            if (!/\d/.test(e.key)) {
                e.preventDefault();
            }
        });
    </script>
</x-guest-layout>