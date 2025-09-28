<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - {{ tenant()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Success Message -->
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Application Submitted Successfully!
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Thank you for your interest in joining {{ tenant()->name }}.
                </p>
            </div>

            <!-- Application Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Position:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $job->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Department:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $job->department->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Location:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $job->location->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Employment Type:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                    </div>
                    @if(session('application_id'))
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Application ID:</span>
                            <span class="text-sm font-medium text-gray-900">{{ session('application_id') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Submitted:</span>
                        <span class="text-sm font-medium text-gray-900">{{ now()->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">What's Next?</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        You will receive a confirmation email shortly
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Our recruitment team will review your application
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        We'll contact you within 5-7 business days
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('careers.index', ['tenant' => tenant()->slug]) }}" 
                   class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    View Other Jobs
                </a>
                <a href="{{ route('careers.show', ['tenant' => tenant()->slug, 'job' => $job->slug]) }}" 
                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    Back to Job Details
                </a>
            </div>

            <!-- Contact Information -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Questions? Contact our HR department at 
                    <a href="mailto:hr@{{ tenant()->slug }}.com" class="text-blue-600 hover:text-blue-800">
                        hr@{{ tenant()->slug }}.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
