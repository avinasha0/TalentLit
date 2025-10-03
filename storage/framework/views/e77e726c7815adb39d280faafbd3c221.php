<?php $__env->startSection('title', 'Deployment Guide — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to deploy TalentLit ATS on your own server, including hosting setup, configuration, and maintenance.'); ?>

<?php
    $seoTitle = 'Deployment Guide — TalentLit Help';
    $seoDescription = 'Learn how to deploy TalentLit ATS on your own server, including hosting setup, configuration, and maintenance.';
    $seoKeywords = 'TalentLit deployment, ATS hosting, self-hosted ATS, server setup';
    $seoAuthor = 'TalentLit';
    $seoImage = asset('logo-talentlit-small.svg');
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('help.index')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        ← Back to Help Center
                    </a>
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    Deployment Guide
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to deploy TalentLit ATS on your own server, including hosting setup and configuration.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Table of Contents -->
                <div class="lg:col-span-9">
                    <div class="sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Deployment Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#system-requirements" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">System Requirements</a>
                            <a href="#hosting-setup" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Hosting Setup</a>
                            <a href="#installation" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Installation</a>
                            <a href="#configuration" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Configuration</a>
                            <a href="#database-setup" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Database Setup</a>
                            <a href="#ssl-setup" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">SSL Setup</a>
                            <a href="#maintenance" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Maintenance</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Deployment Overview</h2>
                            <p class="text-gray-600 mb-4">
                                This guide will help you deploy TalentLit ATS on your own server. You'll learn how to:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Set up a hosting environment with the required software</li>
                                <li>Install and configure TalentLit ATS</li>
                                <li>Set up a database and configure connections</li>
                                <li>Configure SSL and security settings</li>
                                <li>Set up automated backups and maintenance</li>
                                <li>Troubleshoot common deployment issues</li>
                            </ul>
                        </div>

                        <h2 id="system-requirements" class="text-2xl font-bold text-gray-900 mb-6">System Requirements</h2>
                        <p class="text-gray-600 mb-6">
                            Ensure your server meets the minimum requirements for running TalentLit ATS.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Minimum Requirements:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💻</span>
                                        <div>
                                            <strong>Operating System:</strong> Linux (Ubuntu 20.04+ recommended) or Windows Server
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🧠</span>
                                        <div>
                                            <strong>RAM:</strong> Minimum 4GB, 8GB+ recommended for production
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💾</span>
                                        <div>
                                            <strong>Storage:</strong> Minimum 20GB, 100GB+ recommended for production
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🌐</span>
                                        <div>
                                            <strong>Network:</strong> Stable internet connection with static IP recommended
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="hosting-setup" class="text-2xl font-bold text-gray-900 mb-6">Hosting Setup</h2>
                        <p class="text-gray-600 mb-6">
                            Set up your hosting environment with the required software and configurations.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Software:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🐘</span>
                                        <div>
                                            <strong>PHP:</strong> PHP 8.1 or higher with required extensions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗄️</span>
                                        <div>
                                            <strong>Database:</strong> MySQL 8.0+ or PostgreSQL 13+
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🌐</span>
                                        <div>
                                            <strong>Web Server:</strong> Apache 2.4+ or Nginx 1.18+
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📦</span>
                                        <div>
                                            <strong>Composer:</strong> PHP dependency manager
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="installation" class="text-2xl font-bold text-gray-900 mb-6">Installation</h2>
                        <p class="text-gray-600 mb-6">
                            Install TalentLit ATS on your server following these step-by-step instructions.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Installation Steps:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Download Source:</strong> Download the TalentLit source code to your server
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Install Dependencies:</strong> Run <code class="bg-gray-100 px-2 py-1 rounded">composer install</code>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Set Permissions:</strong> Set proper file permissions for web server
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Configure Environment:</strong> Copy and configure the .env file
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Run Migrations:</strong> Run <code class="bg-gray-100 px-2 py-1 rounded">php artisan migrate</code>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Build Assets:</strong> Run <code class="bg-gray-100 px-2 py-1 rounded">npm run build</code>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="configuration" class="text-2xl font-bold text-gray-900 mb-6">Configuration</h2>
                        <p class="text-gray-600 mb-6">
                            Configure TalentLit ATS for your specific environment and requirements.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuration Steps:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Environment Variables:</strong> Configure database, mail, and other settings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>Application Key:</strong> Generate and set the application key
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Mail Configuration:</strong> Set up email sending capabilities
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🌐</span>
                                        <div>
                                            <strong>Domain Configuration:</strong> Set up your domain and subdomain
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="database-setup" class="text-2xl font-bold text-gray-900 mb-6">Database Setup</h2>
                        <p class="text-gray-600 mb-6">
                            Set up and configure your database for TalentLit ATS.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Database Configuration:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗄️</span>
                                        <div>
                                            <strong>Create Database:</strong> Create a new database for TalentLit
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👤</span>
                                        <div>
                                            <strong>Create User:</strong> Create a database user with appropriate permissions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔗</span>
                                        <div>
                                            <strong>Configure Connection:</strong> Update database connection settings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Run Migrations:</strong> Execute database migrations to create tables
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="ssl-setup" class="text-2xl font-bold text-gray-900 mb-6">SSL Setup</h2>
                        <p class="text-gray-600 mb-6">
                            Secure your TalentLit installation with SSL certificates for encrypted communication.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">SSL Configuration:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Obtain Certificate:</strong> Get SSL certificate from Let's Encrypt or CA
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Configure Web Server:</strong> Set up SSL in Apache or Nginx
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔄</span>
                                        <div>
                                            <strong>Auto-Renewal:</strong> Set up automatic certificate renewal
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✅</span>
                                        <div>
                                            <strong>Test SSL:</strong> Verify SSL is working correctly
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="maintenance" class="text-2xl font-bold text-gray-900 mb-6">Maintenance</h2>
                        <p class="text-gray-600 mb-6">
                            Keep your TalentLit installation running smoothly with regular maintenance tasks.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Tasks:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📦</span>
                                        <div>
                                            <strong>Updates:</strong> Regularly update TalentLit and dependencies
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💾</span>
                                        <div>
                                            <strong>Backups:</strong> Set up automated database and file backups
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🧹</span>
                                        <div>
                                            <strong>Cleanup:</strong> Clean up logs, temporary files, and old data
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Monitoring:</strong> Monitor server performance and uptime
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Installation Fails</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check system requirements and dependencies</li>
                                        <li>• Verify file permissions and ownership</li>
                                        <li>• Check error logs for specific issues</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Database Connection Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify database credentials and connection string</li>
                                        <li>• Check if database server is running</li>
                                        <li>• Ensure database user has proper permissions</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check server resources (CPU, RAM, disk space)</li>
                                        <li>• Optimize database queries and indexes</li>
                                        <li>• Enable caching and compression</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="related-topics" class="bg-indigo-50 p-8 rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Topics</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Next Steps</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'settings')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'security')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Security Best Practices</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'integrations')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Integrations</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'troubleshooting')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'contact')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact Support</a></li>
                                        <li><a href="<?php echo e(route('help.index')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\help\deploy.blade.php ENDPATH**/ ?>