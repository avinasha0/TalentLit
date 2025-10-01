<x-public-layout>
    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">HireHub —</span>
                            <span class="block text-blue-600 xl:inline">Modern ATS for Growing Teams</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Streamline your hiring process with powerful tools, intuitive design, and seamless collaboration. 
                            Find the right talent faster than ever before.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="/{{ $tenant ?? 'acme' }}/careers" 
                                   class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    View Careers
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('login') }}" 
                                   class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    Sign In
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <div class="h-56 w-full bg-gradient-to-r from-primary-400 to-primary-600 sm:h-72 md:h-96 lg:w-full lg:h-full flex items-center justify-center">
                <div class="text-white text-center">
                    <svg class="w-32 h-32 mx-auto mb-4 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                    <p class="text-xl font-semibold">Modern Hiring Made Simple</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Logos Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold text-gray-500 uppercase tracking-wide">
                Trusted by growing teams
            </p>
            <div class="mt-6 grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
                <!-- Placeholder logos -->
                <div class="col-span-1 flex justify-center items-center">
                    <div class="h-8 w-24 bg-gray-300 rounded"></div>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <div class="h-8 w-24 bg-gray-300 rounded"></div>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <div class="h-8 w-24 bg-gray-300 rounded"></div>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <div class="h-8 w-24 bg-gray-300 rounded"></div>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <div class="h-8 w-24 bg-gray-300 rounded"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Everything you need to hire great talent
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Powerful features designed to streamline your hiring process
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Post Jobs -->
                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-gray-900">Post Jobs</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Create and manage job postings with our intuitive job builder. Reach candidates across multiple channels.
                        </p>
                    </div>

                    <!-- Track Candidates -->
                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-gray-900">Track Candidates</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Organize and track candidates through your hiring pipeline with our comprehensive candidate management system.
                        </p>
                    </div>

                    <!-- Automate Workflows -->
                    <div class="text-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-gray-900">Automate Workflows</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Set up automated workflows to streamline repetitive tasks and keep your hiring process moving efficiently.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-primary-600">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">50%</div>
                    <div class="text-primary-100">Faster Time-to-Hire</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">10K+</div>
                    <div class="text-primary-100">Candidates Processed</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">95%</div>
                    <div class="text-primary-100">Hiring Manager Satisfaction</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <div class="text-primary-100">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="bg-primary-50 rounded-2xl px-6 py-16 sm:px-16 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Ready to transform your hiring process?
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Join thousands of teams already using HireHub to find and hire the best talent.
                </p>
                <div class="mt-8">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Get Started Today
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
