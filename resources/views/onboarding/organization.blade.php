<x-onboarding-layout 
    :title="'Create Organization'" 
    :subtitle="'Set up your organization to get started'"
>
    <form class="space-y-6" action="{{ route('onboarding.organization.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-200">Organization Name</label>
            <input id="name" name="name" type="text" required
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="Acme Corporation" />
            @error('name')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-gray-200">Organization URL</label>
            <div class="flex rounded-lg shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-600 bg-gray-800 text-gray-400 sm:text-sm">
                    {{ config('app.url') }}/
                </span>
                <input id="slug" name="slug" type="text" required
                    class="flex-1 min-w-0 block w-full rounded-none rounded-r-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                    placeholder="acme-corp" />
            </div>
            <p class="mt-1 text-xs text-gray-400">This will be your organization's unique URL</p>
            @error('slug')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="website" class="block text-sm font-medium text-gray-200">Website (Optional)</label>
            <input id="website" name="website" type="url"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="https://acme.com" />
            @error('website')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="location" class="block text-sm font-medium text-gray-200">Location (Optional)</label>
            <input id="location" name="location" type="text"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="New York, NY" />
            @error('location')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="company_size" class="block text-sm font-medium text-gray-200">Company Size (Optional)</label>
            <select id="company_size" name="company_size"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2">
                <option value="">Select company size</option>
                <option value="1-10">1–10</option>
                <option value="11-50">11–50</option>
                <option value="51-200">51–200</option>
                <option value="201-500">201–500</option>
                <option value="500+">500+</option>
            </select>
            @error('company_size')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create Organization
            </button>
        </div>
    </form>

</x-onboarding-layout>