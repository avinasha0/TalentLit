<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer response — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased py-10 px-4">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
        @php
            $portalUrl = $tenant->getApplicantPortalUrl();
            $lines = match ($variant) {
                'accepted' => ['Thank you', 'We have recorded that you accepted the offer. Your application is now in Pre-Onboarding. '.$tenant->name.' will follow up with next steps.'],
                'rejected' => ['Offer declined', 'We have recorded your decision to decline this offer. We appreciate your time with us.'],
                'discussion_saved' => ['Message sent', 'We have shared your message with the hiring team. They will get back to you.'],
                'already_responded' => ['Already submitted', 'A response for this offer was already recorded. If you need to make a change, please contact '.$tenant->name.' directly.'],
                'not_offered' => ['Unable to respond', 'This application is no longer in the offer stage.'],
                'discussion_invalid' => ['Message required', 'Please go back and enter a message, or use the link from your email again.'],
                default => ['Something went wrong', 'We could not record your response. Please try again from your email or applicant portal.'],
            };
        @endphp
        <h1 class="text-xl font-bold text-slate-900">{{ $lines[0] }}</h1>
        <p class="mt-3 text-sm text-slate-600 leading-relaxed">{{ $lines[1] }}</p>
        <p class="mt-8">
            <a href="{{ $portalUrl }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Open applicant portal →</a>
        </p>
    </div>
</body>
</html>
