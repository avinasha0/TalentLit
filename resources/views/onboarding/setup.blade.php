<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Welcome to {{ $tenant->name }}!</h1>
                        <p class="mt-2 text-sm text-gray-600">Let's complete your setup to get started with hiring</p>
                    </div>

                    <form method="POST" action="{{ route('onboarding.setup.store', $tenant->slug) }}" class="space-y-8">
                        @csrf
                        
                        <!-- Branding Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">🎨 Branding</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-gray-700">Company Logo</label>
                                    <div class="mt-1">
                                        <input id="logo" name="logo" type="file" accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                    </div>
                                </div>
                                <div>
                                    <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                                    <div class="mt-1">
                                        <input id="primary_color" name="primary_color" type="color" value="#6E46AE"
                                               class="h-10 w-full border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Careers Page Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">🌐 Careers Page</h2>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="careers_enabled" name="careers_enabled" type="checkbox" value="1" checked
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="careers_enabled" class="ml-2 block text-sm text-gray-900">
                                        Enable public careers page
                                    </label>
                                </div>
                                <div>
                                    <label for="careers_intro" class="block text-sm font-medium text-gray-700">Introduction Text</label>
                                    <div class="mt-1">
                                        <textarea id="careers_intro" name="careers_intro" rows="3"
                                                  class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                  placeholder="Welcome to our careers page. We're always looking for talented individuals to join our team..."></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label for="consent_text" class="block text-sm font-medium text-gray-700">Application Consent Text</label>
                                    <div class="mt-1">
                                        <textarea id="consent_text" name="consent_text" rows="2"
                                                  class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                  placeholder="By submitting this application, you consent to..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">⚙️ Settings</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                    <div class="mt-1">
                                        <select id="timezone" name="timezone"
                                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="Asia/Kolkata" selected>Asia/Kolkata</option>
                                            <option value="America/New_York">America/New_York</option>
                                            <option value="America/Los_Angeles">America/Los_Angeles</option>
                                            <option value="Europe/London">Europe/London</option>
                                            <option value="Europe/Paris">Europe/Paris</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label for="locale" class="block text-sm font-medium text-gray-700">Language</label>
                                    <div class="mt-1">
                                        <select id="locale" name="locale"
                                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="en" selected>English</option>
                                            <option value="es">Spanish</option>
                                            <option value="fr">French</option>
                                            <option value="de">German</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default Pipeline Info -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Default Pipeline</h2>
                            <p class="text-sm text-gray-600 mb-4">
                                We'll create a default hiring pipeline with these stages:
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Applied</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Screen</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Interview</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Offer</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Hired</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                You can customize these stages later in your job settings.
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Complete Setup
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
