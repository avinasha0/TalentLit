<form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit" class="{{ $attributes->get('class', 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white') }}">
        {{ $slot }}
    </button>
</form>
