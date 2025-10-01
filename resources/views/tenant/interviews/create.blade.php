<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule Interview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Candidate Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">Candidate</h3>
                        <p class="text-sm text-gray-600">{{ $candidate->first_name }} {{ $candidate->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $candidate->primary_email }}</p>
                    </div>

                    <form method="POST" action="{{ route('tenant.interviews.store', ['tenant' => $tenant, 'candidate' => $candidate]) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Job Selection -->
                            <div class="md:col-span-2">
                                <label for="job_id" class="block text-sm font-medium text-gray-700">Job Position</label>
                                <select name="job_id" id="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a job position</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}" {{ old('job_id') == $job->id ? 'selected' : '' }}>
                                            {{ $job->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date and Time -->
                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date & Time</label>
                                <input type="datetime-local" 
                                       name="scheduled_at" 
                                       id="scheduled_at" 
                                       value="{{ old('scheduled_at') }}"
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('scheduled_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                <select name="duration_minutes" id="duration_minutes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select duration</option>
                                    <option value="15" {{ old('duration_minutes') == '15' ? 'selected' : '' }}>15 minutes</option>
                                    <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45 minutes</option>
                                    <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>1 hour</option>
                                    <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                    <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>2 hours</option>
                                </select>
                                @error('duration_minutes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mode -->
                            <div>
                                <label for="mode" class="block text-sm font-medium text-gray-700">Interview Mode</label>
                                <select name="mode" id="mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select mode</option>
                                    <option value="onsite" {{ old('mode') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                                    <option value="remote" {{ old('mode') == 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="phone" {{ old('mode') == 'phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                                @error('mode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location (shown when onsite) -->
                            <div id="location-field" class="hidden">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" 
                                       name="location" 
                                       id="location" 
                                       value="{{ old('location') }}"
                                       placeholder="e.g., Conference Room A, 123 Main St"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meeting Link (shown when remote) -->
                            <div id="meeting-link-field" class="hidden">
                                <label for="meeting_link" class="block text-sm font-medium text-gray-700">Meeting Link</label>
                                <input type="url" 
                                       name="meeting_link" 
                                       id="meeting_link" 
                                       value="{{ old('meeting_link') }}"
                                       placeholder="https://meet.google.com/abc-defg-hij"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('meeting_link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Panelists -->
                            <div class="md:col-span-2">
                                <label for="panelists" class="block text-sm font-medium text-gray-700">Panelists</label>
                                <div class="mt-1 space-y-2 max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    @foreach($users as $user)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="panelists[]" 
                                                   value="{{ $user->id }}"
                                                   {{ in_array($user->id, old('panelists', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $user->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('panelists')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          rows="3" 
                                          placeholder="Any additional notes about this interview..."
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('tenant.candidates.show', ['tenant' => $tenant, 'candidate' => $candidate]) }}" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Schedule Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide location and meeting link fields based on mode selection
        document.getElementById('mode').addEventListener('change', function() {
            const locationField = document.getElementById('location-field');
            const meetingLinkField = document.getElementById('meeting-link-field');
            
            // Hide both fields first
            locationField.classList.add('hidden');
            meetingLinkField.classList.add('hidden');
            
            // Show appropriate field based on selection
            if (this.value === 'onsite') {
                locationField.classList.remove('hidden');
            } else if (this.value === 'remote') {
                meetingLinkField.classList.remove('hidden');
            }
        });

        // Trigger change event on page load if mode is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const modeSelect = document.getElementById('mode');
            if (modeSelect.value) {
                modeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>
