<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for {{ $job->title }} - {{ $tenantModel->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --brand: {{ $branding && $branding->primary_color ? $branding->primary_color : '#4f46e5' }};
        }
    </style>
</head>
<body class="bg-white">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('careers.index', ['tenant' => $tenantModel->slug]) }}" class="flex items-center space-x-2">
                            @if($branding && $branding->logo_path)
                                <img src="{{ asset('storage/' . $branding->logo_path) }}" alt="{{ $tenantModel->name }} Logo" class="h-8">
                            @else
                                <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-8">
                            @endif
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('careers.index', ['tenant' => $tenantModel->slug]) }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            All Jobs
                        </a>
                        <a href="{{ route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug]) }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            View Job
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="text-center">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center justify-center space-x-2 text-sm text-indigo-200 mb-6">
                        <a href="{{ route('careers.index', ['tenant' => $tenantModel->slug]) }}" class="hover:text-white transition-colors">Careers</a>
                        <span>></span>
                        <a href="{{ route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug]) }}" class="hover:text-white transition-colors">{{ $job->title }}</a>
                        <span>></span>
                        <span class="text-white">Apply</span>
                    </nav>
                    
                    <!-- Job Title -->
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                        Apply for {{ $job->title }}
                    </h1>
                    
                    <!-- Job Meta -->
                    <div class="flex flex-wrap items-center justify-center gap-6 text-indigo-100 mb-8">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>{{ $job->department->name }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $job->location->name }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Application Form Section -->
        <section class="py-20 bg-white">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-2xl p-8 lg:p-12 border border-gray-100">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Application Form</h2>
                        <p class="text-gray-600">Fill out the form below to apply for this position. We'll review your application and get back to you soon.</p>
                    </div>

                    <!-- Application Form -->
                    <div class="bg-white rounded-xl p-8 shadow-lg">

                        @if($errors->any())
                            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-red-800">Please correct the following errors:</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('careers.apply.store', ['tenant' => $tenantModel->slug, 'job' => $job->slug]) }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              class="space-y-8">
                            @csrf

                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="first_name" 
                                               id="first_name"
                                               value="{{ old('first_name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('first_name') border-red-500 @enderror">
                                        @error('first_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="last_name" 
                                               id="last_name"
                                               value="{{ old('last_name') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('last_name') border-red-500 @enderror">
                                        @error('last_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               id="email"
                                               value="{{ old('email') }}"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number
                                        </label>
                                        <input type="tel" 
                                               name="phone" 
                                               id="phone"
                                               value="{{ old('phone') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('phone') border-red-500 @enderror">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Resume Upload -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Resume Upload</h3>
                                <div>
                                    <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                                        Resume (PDF, DOC, DOCX) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" 
                                           name="resume" 
                                           id="resume"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('resume') border-red-500 @enderror">
                                    <p class="mt-2 text-sm text-gray-500">Maximum file size: 5MB</p>
                                    @error('resume')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Custom Questions -->
                            @if($job->applicationQuestions->count() > 0)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Additional Information</h3>
                            <div class="space-y-6">
                                @foreach($job->applicationQuestions as $question)
                                    @php
                                        $isRequired = $question->pivot->required_override ?? $question->required;
                                        $fieldName = "question_{$question->id}";
                                        $oldValue = old($fieldName);
                                    @endphp
                                    
                                    <div>
                                        <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ $question->label }}
                                            @if($isRequired)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        
                                        @switch($question->type)
                                            @case('short_text')
                                                <input type="text" 
                                                       name="{{ $fieldName }}" 
                                                       id="{{ $fieldName }}"
                                                       value="{{ $oldValue }}"
                                                       @if($isRequired) required @endif
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">
                                                @break
                                            
                                            @case('long_text')
                                                <textarea name="{{ $fieldName }}" 
                                                          id="{{ $fieldName }}"
                                                          rows="4"
                                                          @if($isRequired) required @endif
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">{{ $oldValue }}</textarea>
                                                @break
                                            
                                            @case('email')
                                                <input type="email" 
                                                       name="{{ $fieldName }}" 
                                                       id="{{ $fieldName }}"
                                                       value="{{ $oldValue }}"
                                                       @if($isRequired) required @endif
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">
                                                @break
                                            
                                            @case('phone')
                                                <input type="tel" 
                                                       name="{{ $fieldName }}" 
                                                       id="{{ $fieldName }}"
                                                       value="{{ $oldValue }}"
                                                       @if($isRequired) required @endif
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">
                                                @break
                                            
                                            @case('select')
                                                <select name="{{ $fieldName }}" 
                                                        id="{{ $fieldName }}"
                                                        @if($isRequired) required @endif
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">
                                                    <option value="">Please select...</option>
                                                    @if($question->options)
                                                        @foreach($question->options as $option)
                                                            <option value="{{ $option }}" {{ $oldValue == $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @break
                                            
                                            @case('multi_select')
                                                <div class="space-y-2">
                                                    @if($question->options)
                                                        @foreach($question->options as $option)
                                                            <label class="flex items-center">
                                                                <input type="checkbox" 
                                                                       name="{{ $fieldName }}[]" 
                                                                       value="{{ $option }}"
                                                                       {{ in_array($option, (array)$oldValue) ? 'checked' : '' }}
                                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                                <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                                            </label>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                @break
                                            
                                            @case('checkbox')
                                                <label class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="{{ $fieldName }}" 
                                                           value="1"
                                                           {{ $oldValue ? 'checked' : '' }}
                                                           @if($isRequired) required @endif
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                                </label>
                                                @break
                                            
                                            @case('file')
                                                <input type="file" 
                                                       name="{{ $fieldName }}" 
                                                       id="{{ $fieldName }}"
                                                       @if($isRequired) required @endif
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error($fieldName) border-red-500 @enderror">
                                                <p class="mt-1 text-sm text-gray-600">Maximum file size: 5MB</p>
                                                @break
                                        @endswitch
                                        
                                        @error($fieldName)
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                            <!-- Consent Checkbox -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           name="consent" 
                                           id="consent"
                                           value="1"
                                           {{ old('consent') ? 'checked' : '' }}
                                           required
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded @error('consent') border-red-500 @enderror">
                                </div>
                                <div class="ml-3">
                                    <label for="consent" class="text-sm font-medium text-gray-700">
                                        @if($tenantModel->consent_text)
                                            {{ $tenantModel->consent_text }} <span class="text-red-500">*</span>
                                        @else
                                            I agree to the terms and conditions and consent to the processing of my personal data for recruitment purposes. <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                </div>
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="flex justify-center">
                                <x-recaptcha />
                            </div>

                            <!-- Submit Button -->
                            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8">
                                <a href="{{ route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug]) }}" 
                                   class="px-8 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors text-center">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
