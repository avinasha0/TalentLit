<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discuss offer — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased py-10 px-4">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
        <h1 class="text-xl font-bold text-slate-900">Request a discussion</h1>
        <p class="mt-2 text-sm text-slate-600">{{ $tenant->name }} — {{ $application->jobOpening->title ?? 'Application' }}</p>

        <form method="post" action="{{ $submitUrl }}" class="mt-6 space-y-4">
            @csrf
            <label for="message" class="block text-sm font-medium text-slate-700">Your message</label>
            <textarea id="message" name="message" rows="6" required maxlength="10000"
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm"
                placeholder="Share questions or topics you would like to discuss about the offer."></textarea>

            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">
                Submit
            </button>
        </form>
    </div>
</body>
</html>
