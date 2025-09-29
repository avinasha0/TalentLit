@props(['class' => ''])

<div class="overflow-hidden {{ $class }}">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </table>
    </div>
</div>
