<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Simple header -->
        <div class="bg-white shadow">
            <div class="px-4 py-6">
                <h1 class="text-2xl font-bold text-gray-900">Simple Dashboard</h1>
                <p class="text-gray-600">Testing without complex layout</p>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                <p><strong>Debug:</strong> Simple dashboard is working!</p>
                <p><strong>Data:</strong> Open Jobs: 5, Active Candidates: 12</p>
            </div>

            <!-- Simple cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900">Open Jobs</h3>
                    <p class="text-3xl font-bold text-blue-600">5</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900">Active Candidates</h3>
                    <p class="text-3xl font-bold text-green-600">12</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900">Interviews This Week</h3>
                    <p class="text-3xl font-bold text-purple-600">3</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900">Total Hires</h3>
                    <p class="text-3xl font-bold text-orange-600">2</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/simple-dashboard.blade.php ENDPATH**/ ?>