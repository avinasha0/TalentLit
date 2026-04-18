@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $skills = [];
    if (!empty($requisition->skills)) {
        $decoded = json_decode($requisition->skills, true);
        if (is_array($decoded)) {
            $skills = $decoded;
        } else {
            $skills = array_values(array_filter(array_map('trim', explode(',', (string) $requisition->skills))));
        }
    }
@endphp

<x-app-layout :tenant="$tenant">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Requisition</h1>
                <p class="text-sm text-gray-600">Update requisition details and save changes.</p>
            </div>
            <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                Back
            </a>
        </div>

        <x-card>
            <form method="POST" action="{{ tenantRoute('tenant.requisitions.update', [$tenantSlug, $requisition->id]) }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input id="department" name="department" type="text"
                               value="{{ old('department', $requisition->department) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('department') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                        <input id="job_title" name="job_title" type="text"
                               value="{{ old('job_title', $requisition->job_title) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('job_title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="justification" class="block text-sm font-medium text-gray-700 mb-1">Justification</label>
                    <textarea id="justification" name="justification" rows="4"
                              class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">{{ old('justification', $requisition->justification) }}</textarea>
                    @error('justification') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="budget_min" class="block text-sm font-medium text-gray-700 mb-1">Budget Min</label>
                        <input id="budget_min" name="budget_min" type="number" min="0"
                               value="{{ old('budget_min', $requisition->budget_min) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('budget_min') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="budget_max" class="block text-sm font-medium text-gray-700 mb-1">Budget Max</label>
                        <input id="budget_max" name="budget_max" type="number" min="0"
                               value="{{ old('budget_max', $requisition->budget_max) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('budget_max') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="headcount" class="block text-sm font-medium text-gray-700 mb-1">Headcount</label>
                        <input id="headcount" name="headcount" type="number" min="1"
                               value="{{ old('headcount', $requisition->headcount) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('headcount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-1">Contract Type</label>
                        <select id="contract_type" name="contract_type" class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            @foreach(['Full-time','Part-time','Contract','Intern','Temporary'] as $type)
                                <option value="{{ $type }}" @selected(old('contract_type', $requisition->contract_type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('contract_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (months)</label>
                        <input id="duration" name="duration" type="number" min="1" max="60"
                               value="{{ old('duration', $requisition->duration) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('duration') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select id="priority" name="priority" class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            @foreach(['Low','Medium','High'] as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', $requisition->priority) === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        @error('priority') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="experience_min" class="block text-sm font-medium text-gray-700 mb-1">Experience Min</label>
                        <input id="experience_min" name="experience_min" type="number" min="0" max="50"
                               value="{{ old('experience_min', $requisition->experience_min) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('experience_min') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="experience_max" class="block text-sm font-medium text-gray-700 mb-1">Experience Max</label>
                        <input id="experience_max" name="experience_max" type="number" min="0" max="50"
                               value="{{ old('experience_max', $requisition->experience_max) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('experience_max') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input id="location" name="location" type="text"
                               value="{{ old('location', $requisition->location) }}"
                               class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                        @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="skills_visible" class="block text-sm font-medium text-gray-700 mb-1">Skills (comma separated)</label>
                    <input id="skills_visible" name="skills_visible" type="text"
                           value="{{ old('skills_visible', implode(', ', $skills)) }}"
                           class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    <input type="hidden" id="skills" name="skills" value="{{ old('skills', json_encode($skills)) }}">
                    @error('skills') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea id="additional_notes" name="additional_notes" rows="3"
                              class="w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring-purple-500">{{ old('additional_notes', $requisition->additional_notes) }}</textarea>
                    @error('additional_notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700">
                        Save Changes
                    </button>
                    <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}"
                       class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        (function () {
            const skillsVisible = document.getElementById('skills_visible');
            const skillsHidden = document.getElementById('skills');
            if (!skillsVisible || !skillsHidden) return;

            const syncSkills = () => {
                const skills = skillsVisible.value
                    .split(',')
                    .map(s => s.trim())
                    .filter(Boolean);
                skillsHidden.value = JSON.stringify(skills);
            };

            skillsVisible.addEventListener('input', syncSkills);
            syncSkills();
        })();
    </script>
    @endpush
</x-app-layout>
