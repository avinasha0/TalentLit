@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Create Requisition', 'url' => null]
    ];
    
    // Get departments and locations for dropdowns
    $departments = \App\Models\Department::orderBy('name')->get();
    $globalDepartments = \App\Models\GlobalDepartment::orderBy('name')->get();
    $locations = \App\Models\Location::orderBy('name')->get();
    $globalLocations = \App\Models\GlobalLocation::orderBy('name')->get();
    
    // Load draft if exists
    $draft = session('requisition_draft');
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6" 
         x-data="requisitionForm()" 
         x-init="init()"
         @beforeunload.window="handleBeforeUnload($event)"
         id="requisition-create-page">
        
        <!-- Sticky Header (Task 90) -->
        <div class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200 -mx-6 px-6 py-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Requisition</h1>
                    <p class="mt-1 text-sm text-gray-500">Fill in all required fields marked with *</p>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Help Link (Task 91) -->
                    <button type="button" 
                            @click="showHelpModal = true"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                            title="Help">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                    <!-- Cancel Button (Task 92) -->
                    <a href="{{ tenantRoute('tenant.requisitions.index', $tenantSlug) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </div>
            
            <!-- Progress Indicator (Task 29) -->
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                    <span>Form Progress</span>
                    <span x-text="`${progressPercentage}% completed`"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" 
                         :style="`width: ${progressPercentage}%`"></div>
                </div>
            </div>
        </div>

        <!-- Info Banner (Task 34) -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-blue-800">All fields marked <span class="font-semibold">*</span> are mandatory.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form Column -->
            <div class="lg:col-span-2 space-y-6">
                <form id="requisition-form" 
                      method="POST" 
                      action="{{ tenantRoute('tenant.requisitions.store', $tenantSlug) }}"
                      enctype="multipart/form-data"
                      @submit.prevent="handleSubmit"
                      novalidate>
                    @csrf

                    <!-- Basic Information Section (Task 89) -->
                    <x-card>
                        <x-slot name="header">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                            </div>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Department Dropdown (Task 1) -->
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                                    Department <span class="text-red-500">*</span>
                                    <button type="button" 
                                            @click="showTooltip('department')"
                                            class="ml-1 text-gray-400 hover:text-gray-600"
                                            title="Select the department for this requisition">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </label>
                                <select id="department" 
                                        name="department"
                                        x-model="formData.department"
                                        @change="updatePreview(); validateField('department')"
                                        :class="{'border-red-500': errors.department}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                        required
                                        aria-label="Department selection"
                                        aria-required="true">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department', '') == $dept->name ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                    @foreach($globalDepartments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department', '') == $dept->name ? 'selected' : '' }}>
                                            {{ $dept->name }} (Global)
                                        </option>
                                    @endforeach
                                </select>
                                <p x-show="errors.department" x-text="errors.department" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Job Title Input with Autocomplete (Task 2) -->
                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Job Title <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           id="job_title"
                                           name="job_title"
                                           x-model="formData.job_title"
                                           @input.debounce.300ms="autocompleteJobTitle($event.target.value); updatePreview(); validateField('job_title')"
                                           @focus="showJobTitleSuggestions = true"
                                           @blur="setTimeout(() => showJobTitleSuggestions = false, 200)"
                                           :class="{'border-red-500': errors.job_title}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           placeholder="e.g., Senior Software Engineer"
                                           autocomplete="off"
                                           required
                                           aria-label="Job title"
                                           aria-required="true"
                                           aria-autocomplete="list"
                                           aria-expanded="false"
                                           :aria-controls="showJobTitleSuggestions ? 'job-title-suggestions' : null">
                                    <!-- Autocomplete Suggestions (Task 25) -->
                                    <div x-show="showJobTitleSuggestions && jobTitleSuggestions.length > 0"
                                         x-cloak
                                         id="job-title-suggestions"
                                         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                                         role="listbox">
                                        <template x-for="(suggestion, index) in jobTitleSuggestions" :key="index">
                                            <button type="button"
                                                    @click="selectJobTitle(suggestion)"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                                    :class="{'bg-gray-100': index === selectedJobTitleIndex}"
                                                    role="option">
                                                <span x-text="suggestion"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <p x-show="errors.job_title" x-text="errors.job_title" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Justification Textarea (Task 3) -->
                            <div>
                                <label for="justification" class="block text-sm font-medium text-gray-700 mb-1">
                                    Justification <span class="text-red-500">*</span>
                                </label>
                                <textarea id="justification"
                                          name="justification"
                                          x-model="formData.justification"
                                          @input="autoExpandTextarea($event.target); updatePreview(); validateField('justification')"
                                          :class="{'border-red-500': errors.justification}"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900 resize-none"
                                          rows="4"
                                          placeholder="Explain why this requisition is needed..."
                                          required
                                          aria-label="Justification"
                                          aria-required="true"></textarea>
                                <!-- Character Counter (Task 28) -->
                                <div class="flex justify-between items-center mt-1">
                                    <p x-show="errors.justification" x-text="errors.justification" class="text-sm text-red-600"></p>
                                    <span class="text-xs text-gray-500 ml-auto" x-text="`${formData.justification.length} characters`"></span>
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Role Details Section -->
                    <x-card>
                        <x-slot name="header">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-900">Role Details</h2>
                            </div>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Contract Type Dropdown (Task 5) -->
                            <div>
                                <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contract Type <span class="text-red-500">*</span>
                                </label>
                                <select id="contract_type"
                                        name="contract_type"
                                        x-model="formData.contract_type"
                                        @change="toggleDurationField(); updatePreview(); validateField('contract_type')"
                                        :class="{'border-red-500': errors.contract_type}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                        required
                                        aria-label="Contract type"
                                        aria-required="true">
                                    <option value="">Select Contract Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Intern">Intern</option>
                                    <option value="Temporary">Temporary</option>
                                </select>
                                <p x-show="errors.contract_type" x-text="errors.contract_type" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Duration Field (Task 6) - Only shown for intern/contract -->
                            <div x-show="showDurationField" 
                                 x-transition
                                 x-cloak>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                                    Duration (Months) <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="duration"
                                       name="duration"
                                       x-model="formData.duration"
                                       @input="updatePreview(); validateField('duration')"
                                       :class="{'border-red-500': errors.duration}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                       min="1"
                                       max="60"
                                       placeholder="e.g., 6"
                                       :required="showDurationField"
                                       aria-label="Duration in months"
                                       :aria-required="showDurationField">
                                <p x-show="errors.duration" x-text="errors.duration" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Experience Min/Max Fields (Task 8) -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="experience_min" class="block text-sm font-medium text-gray-700 mb-1">
                                        Min Experience (Years) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           id="experience_min"
                                           name="experience_min"
                                           x-model="formData.experience_min"
                                           @input="validateExperienceRange(); updatePreview(); validateField('experience_min')"
                                           :class="{'border-red-500': errors.experience_min}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           min="0"
                                           max="50"
                                           required
                                           aria-label="Minimum experience in years"
                                           aria-required="true">
                                    <p x-show="errors.experience_min" x-text="errors.experience_min" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label for="experience_max" class="block text-sm font-medium text-gray-700 mb-1">
                                        Max Experience (Years)
                                    </label>
                                    <input type="number"
                                           id="experience_max"
                                           name="experience_max"
                                           x-model="formData.experience_max"
                                           @input="validateExperienceRange(); updatePreview(); validateField('experience_max')"
                                           :class="{'border-red-500': errors.experience_max}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           min="0"
                                           max="50"
                                           aria-label="Maximum experience in years">
                                    <p x-show="errors.experience_max" x-text="errors.experience_max" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>

                            <!-- Headcount Input (Task 9) -->
                            <div>
                                <label for="headcount" class="block text-sm font-medium text-gray-700 mb-1">
                                    Headcount <span class="text-red-500">*</span>
                                    <button type="button" 
                                            @click="showTooltip('headcount')"
                                            class="ml-1 text-gray-400 hover:text-gray-600"
                                            title="Number of positions to fill">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </label>
                                <input type="number"
                                       id="headcount"
                                       name="headcount"
                                       x-model="formData.headcount"
                                       @input="updatePreview(); validateField('headcount')"
                                       :class="{'border-red-500': errors.headcount}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                       min="1"
                                       value="1"
                                       required
                                       aria-label="Number of positions"
                                       aria-required="true">
                                <p x-show="errors.headcount" x-text="errors.headcount" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Priority Dropdown (Task 10) -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority"
                                        name="priority"
                                        x-model="formData.priority"
                                        @change="updatePreview(); validateField('priority')"
                                        :class="{'border-red-500': errors.priority}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                        required
                                        aria-label="Priority level"
                                        aria-required="true">
                                    <option value="">Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium" selected>Medium</option>
                                    <option value="High">High</option>
                                </select>
                                <p x-show="errors.priority" x-text="errors.priority" class="mt-1 text-sm text-red-600"></p>
                            </div>

                            <!-- Location Field (Task 11) -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="location"
                                       name="location"
                                       x-model="formData.location"
                                       @input="autoFillLocation(); updatePreview(); validateField('location')"
                                       list="location-suggestions"
                                       :class="{'border-red-500': errors.location}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                       placeholder="e.g., New York, Remote, Hybrid"
                                       required
                                       aria-label="Job location"
                                       aria-required="true">
                                <datalist id="location-suggestions">
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->name }}">
                                    @endforeach
                                    @foreach($globalLocations as $loc)
                                        <option value="{{ $loc->name }}">
                                    @endforeach
                                    <option value="Remote">
                                    <option value="Hybrid">
                                </datalist>
                                <p x-show="errors.location" x-text="errors.location" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Budget Section -->
                    <x-card>
                        <x-slot name="header">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-900">Budget</h2>
                            </div>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Budget Min & Max Fields (Task 4) -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="budget_min" class="block text-sm font-medium text-gray-700 mb-1">
                                        Budget Min <span class="text-red-500">*</span>
                                        <button type="button" 
                                                @click="showTooltip('budget')"
                                                class="ml-1 text-gray-400 hover:text-gray-600"
                                                title="Minimum salary budget">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </label>
                                    <input type="number"
                                           id="budget_min"
                                           name="budget_min"
                                           x-model="formData.budget_min"
                                           @input="validateBudgetRange(); updatePreview(); validateField('budget_min')"
                                           :class="{'border-red-500': errors.budget_min}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           min="0"
                                           step="1000"
                                           required
                                           aria-label="Minimum budget"
                                           aria-required="true">
                                    <p x-show="errors.budget_min" x-text="errors.budget_min" class="mt-1 text-sm text-red-600"></p>
                                </div>
                                <div>
                                    <label for="budget_max" class="block text-sm font-medium text-gray-700 mb-1">
                                        Budget Max <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           id="budget_max"
                                           name="budget_max"
                                           x-model="formData.budget_max"
                                           @input="validateBudgetRange(); updatePreview(); validateField('budget_max')"
                                           :class="{'border-red-500': errors.budget_max}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           min="0"
                                           step="1000"
                                           required
                                           aria-label="Maximum budget"
                                           aria-required="true">
                                    <p x-show="errors.budget_max" x-text="errors.budget_max" class="mt-1 text-sm text-red-600"></p>
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Skills Section -->
                    <x-card>
                        <x-slot name="header">
                            <h2 class="text-lg font-semibold text-gray-900">Skills</h2>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Skills Tag Input (Task 7) -->
                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">
                                    Skills <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                           id="skills-input"
                                           x-model="skillInput"
                                           @input.debounce.300ms="autocompleteSkills($event.target.value)"
                                           @keydown.enter.prevent="addSkill()"
                                           @keydown.comma.prevent="addSkill()"
                                           @focus="showSkillSuggestions = true"
                                           @blur="setTimeout(() => showSkillSuggestions = false, 200)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900"
                                           placeholder="Type skills and press Enter or comma"
                                           autocomplete="off"
                                           aria-label="Add skills">
                                    <!-- Skill Suggestions (Task 26) -->
                                    <div x-show="showSkillSuggestions && skillSuggestions.length > 0"
                                         x-cloak
                                         class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                                         role="listbox">
                                        <template x-for="(suggestion, index) in skillSuggestions" :key="index">
                                            <button type="button"
                                                    @click="selectSkill(suggestion)"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                                    role="option">
                                                <span x-text="suggestion"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <!-- Skills Tags Display -->
                                <div class="mt-2 flex flex-wrap gap-2" id="skills-container">
                                    <template x-for="(skill, index) in formData.skills" :key="index">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <span x-text="skill"></span>
                                            <button type="button"
                                                    @click="removeSkill(index)"
                                                    class="ml-2 text-purple-600 hover:text-purple-800"
                                                    aria-label="Remove skill">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                </div>
                                <input type="hidden" name="skills" :value="JSON.stringify(formData.skills)">
                                <p x-show="errors.skills" x-text="errors.skills" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Attachments Section -->
                    <x-card>
                        <x-slot name="header">
                            <h2 class="text-lg font-semibold text-gray-900">Attachments</h2>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Attachments Upload (Task 12) -->
                            <div>
                                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">
                                    Job Description (PDF/DOC) <span class="text-xs text-gray-500">(Max 5MB each)</span>
                                </label>
                                <input type="file"
                                       id="attachments"
                                       name="attachments[]"
                                       @change="handleFileUpload($event)"
                                       accept=".pdf,.doc,.docx"
                                       multiple
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                       aria-label="Upload job description files">
                                <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX (Max 5MB per file)</p>
                                <!-- Uploaded Files Display -->
                                <div x-show="uploadedFiles.length > 0" class="mt-2 space-y-2">
                                    <template x-for="(file, index) in uploadedFiles" :key="index">
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded border">
                                            <span class="text-sm text-gray-700" x-text="file.name"></span>
                                            <button type="button"
                                                    @click="removeFile(index)"
                                                    class="text-red-600 hover:text-red-800"
                                                    aria-label="Remove file">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <p x-show="errors.attachments" x-text="errors.attachments" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Additional Notes Section -->
                    <x-card>
                        <x-slot name="header">
                            <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
                        </x-slot>

                        <div class="space-y-4">
                            <!-- Additional Notes Textarea (Task 13) -->
                            <div>
                                <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Additional Notes
                                </label>
                                <textarea id="additional_notes"
                                          name="additional_notes"
                                          x-model="formData.additional_notes"
                                          @input="autoExpandTextarea($event.target); updatePreview()"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-900 resize-none"
                                          rows="4"
                                          placeholder="Any additional information or special requirements..."
                                          aria-label="Additional notes"></textarea>
                            </div>
                        </div>
                    </x-card>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <!-- Save Draft Button (Task 30, 44) -->
                            <button type="button"
                                    @click="saveDraft()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                Save Draft
                            </button>
                            <!-- Clear Form Button (Task 31) -->
                            <button type="button"
                                    @click="clearForm()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                Clear Form
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <!-- Save as Draft Button -->
                            <button type="button"
                                    :disabled="!isFormValid || isSubmitting"
                                    @click="submitAction = 'draft'; handleSubmit($event)"
                                    class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isSubmitting">Save as Draft</span>
                                <span x-show="isSubmitting && submitAction === 'draft'" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                            <!-- Submit For Approval Button (Task 43) -->
                            <button type="button"
                                    :disabled="!isFormValid || isSubmitting"
                                    @click="submitAction = 'approval'; handleSubmit($event)"
                                    class="px-6 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isSubmitting">Submit For Approval</span>
                                <span x-show="isSubmitting && submitAction === 'approval'" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Submitting...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Preview Panel (Tasks 56-58) -->
            <div class="lg:col-span-1">
                <x-card class="sticky top-24">
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
                            <button type="button"
                                    @click="previewExpanded = !previewExpanded"
                                    class="text-gray-400 hover:text-gray-600"
                                    :aria-expanded="previewExpanded"
                                    aria-label="Toggle preview">
                                <svg x-show="!previewExpanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                <svg x-show="previewExpanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </x-slot>

                    <div x-show="previewExpanded" x-transition class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Job Title</h3>
                            <p class="text-sm text-gray-900" x-text="formData.job_title || 'Not specified'"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Department</h3>
                            <p class="text-sm text-gray-900" x-text="formData.department || 'Not specified'"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Budget</h3>
                            <p class="text-sm text-gray-900" x-text="formatBudget()"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Headcount</h3>
                            <p class="text-sm text-gray-900" x-text="formData.headcount || 'Not specified'"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Priority</h3>
                            <p class="text-sm text-gray-900" x-text="formData.priority || 'Not specified'"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Contract Type</h3>
                            <p class="text-sm text-gray-900" x-text="formData.contract_type || 'Not specified'"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Skills</h3>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <template x-for="(skill, index) in formData.skills" :key="index">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800" x-text="skill"></span>
                                </template>
                                <span x-show="formData.skills.length === 0" class="text-xs text-gray-400">No skills added</span>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <!-- Confirm Before Submit Modal (Task 32) -->
        <div x-show="showConfirmModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showConfirmModal = false"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            <span x-show="submitAction === 'approval'">Confirm Submission for Approval</span>
                            <span x-show="submitAction === 'draft'">Confirm Save as Draft</span>
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><strong>Job Title:</strong> <span x-text="formData.job_title"></span></p>
                            <p><strong>Department:</strong> <span x-text="formData.department"></span></p>
                            <p><strong>Headcount:</strong> <span x-text="formData.headcount"></span></p>
                            <p><strong>Priority:</strong> <span x-text="formData.priority"></span></p>
                        </div>
                        <p class="text-sm text-gray-700" x-show="submitAction === 'approval'">
                            This requisition will be submitted for approval and cannot be edited until changes are requested.
                        </p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                                @click="confirmSubmit()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="submitAction === 'approval'">Confirm & Submit for Approval</span>
                            <span x-show="submitAction === 'draft'">Confirm & Save as Draft</span>
                        </button>
                        <button type="button"
                                @click="showConfirmModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Modal (Task 91) -->
        <div x-show="showHelpModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             @click.self="showHelpModal = false">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Help - Create Requisition</h3>
                        <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm text-gray-600">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Required Fields</h4>
                            <p>All fields marked with * are mandatory and must be filled before submission.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Draft Saving</h4>
                            <p>Your form is automatically saved every 15 seconds. You can also manually save drafts at any time.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">File Uploads</h4>
                            <p>You can upload job description files in PDF or DOC format. Maximum file size is 5MB per file.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <!-- Offline Banner (Task 65) -->
        <div x-show="isOffline" 
             x-cloak
             class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-auto bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>You are currently offline. Changes will be saved when connection is restored.</span>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Global flag to prevent duplicate draft loads across page instances
    if (typeof window.draftLoadInProgress === 'undefined') {
        window.draftLoadInProgress = false;
    }
    
    function requisitionForm() {
        return {
            // Form Data - Start with empty form by default
            formData: {
                department: @json(old('department', '')),
                job_title: @json(old('job_title', '')),
                justification: @json(old('justification', '')),
                budget_min: @json(old('budget_min', '')),
                budget_max: @json(old('budget_max', '')),
                contract_type: @json(old('contract_type', '')),
                duration: @json(old('duration', '')),
                skills: @json(old('skills', [])),
                experience_min: @json(old('experience_min', '')),
                experience_max: @json(old('experience_max', '')),
                headcount: @json(old('headcount', 1)),
                priority: @json(old('priority', 'Medium')),
                location: @json(old('location', '')),
                additional_notes: @json(old('additional_notes', ''))
            },
            
            // UI State
            errors: {},
            showDurationField: false,
            showJobTitleSuggestions: false,
            showSkillSuggestions: false,
            jobTitleSuggestions: [],
            skillSuggestions: [],
            selectedJobTitleIndex: -1,
            skillInput: '',
            uploadedFiles: [],
            isSubmitting: false,
            showConfirmModal: false,
            showHelpModal: false,
            previewExpanded: true,
            submitAction: 'draft', // 'draft' or 'approval'
            isOffline: false,
            hasUnsavedChanges: false,
            submitAction: 'draft', // 'draft' or 'approval'
            autosaveInterval: null,
            
            // Computed Properties
            get isFormValid() {
                return this.validateForm();
            },
            
            get progressPercentage() {
                const fields = ['department', 'job_title', 'justification', 'budget_min', 'budget_max', 
                               'contract_type', 'experience_min', 'headcount', 'priority', 'location', 'skills'];
                const filled = fields.filter(field => {
                    if (field === 'skills') return this.formData.skills.length > 0;
                    if (field === 'duration') return !this.showDurationField || this.formData.duration;
                    return this.formData[field] && this.formData[field].toString().trim() !== '';
                }).length;
                return Math.round((filled / fields.length) * 100);
            },
            
            // Initialization
            init() {
                // Auto-focus first field (Task 35)
                this.$nextTick(() => {
                    const firstField = document.getElementById('department');
                    if (firstField) firstField.focus();
                });
                
                // By default, show empty form (no auto-load)
                // Only load draft if explicitly requested via URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('load_draft') === 'true') {
                    this.loadDraft();
                }
                // Otherwise, start with empty form
                
                // Setup autosave (Task 38)
                this.setupAutosave();
                
                // Monitor online/offline status (Task 65)
                window.addEventListener('online', () => {
                    this.isOffline = false;
                    this.showToast('Connection restored', 'success');
                });
                window.addEventListener('offline', () => {
                    this.isOffline = true;
                    this.showToast('You are offline', 'warning');
                });
                
                // Check if contract type requires duration (Task 23)
                this.toggleDurationField();
                
                // Prevent duplicate submissions (Task 66)
                let isSubmitting = false;
                document.getElementById('requisition-form').addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    isSubmitting = true;
                });
            },
            
            // Validation Methods (Tasks 14-22)
            validateForm() {
                this.errors = {};
                
                // Required fields
                if (!this.formData.department) this.errors.department = 'Department is required';
                if (!this.formData.job_title || this.formData.job_title.trim() === '') {
                    this.errors.job_title = 'Job title is required';
                }
                if (!this.formData.justification || this.formData.justification.trim() === '') {
                    this.errors.justification = 'Justification is required';
                }
                if (!this.formData.budget_min) this.errors.budget_min = 'Minimum budget is required';
                if (!this.formData.budget_max) this.errors.budget_max = 'Maximum budget is required';
                if (!this.formData.contract_type) this.errors.contract_type = 'Contract type is required';
                if (!this.formData.experience_min) this.errors.experience_min = 'Minimum experience is required';
                if (!this.formData.headcount || this.formData.headcount < 1) {
                    this.errors.headcount = 'Headcount must be at least 1';
                }
                if (!this.formData.priority) this.errors.priority = 'Priority is required';
                if (!this.formData.location || this.formData.location.trim() === '') {
                    this.errors.location = 'Location is required';
                }
                if (this.formData.skills.length === 0) {
                    this.errors.skills = 'At least one skill is required';
                }
                
                // Conditional validation
                if (this.showDurationField && !this.formData.duration) {
                    this.errors.duration = 'Duration is required for this contract type';
                }
                
                // Range validations
                this.validateBudgetRange();
                this.validateExperienceRange();
                
                return Object.keys(this.errors).length === 0;
            },
            
            validateField(fieldName) {
                // Individual field validation
                delete this.errors[fieldName];
                
                switch(fieldName) {
                    case 'budget_min':
                    case 'budget_max':
                        this.validateBudgetRange();
                        break;
                    case 'experience_min':
                    case 'experience_max':
                        this.validateExperienceRange();
                        break;
                    case 'headcount':
                        if (!this.formData.headcount || this.formData.headcount < 1) {
                            this.errors.headcount = 'Headcount must be at least 1';
                        }
                        break;
                    case 'skills':
                        if (this.formData.skills.length === 0) {
                            this.errors.skills = 'At least one skill is required';
                        }
                        break;
                }
            },
            
            validateBudgetRange() {
                if (this.formData.budget_min && this.formData.budget_max) {
                    if (parseInt(this.formData.budget_max) < parseInt(this.formData.budget_min)) {
                        this.errors.budget_max = 'Maximum budget must be greater than or equal to minimum budget';
                    } else {
                        delete this.errors.budget_max;
                    }
                }
            },
            
            validateExperienceRange() {
                if (this.formData.experience_min && this.formData.experience_max) {
                    if (parseInt(this.formData.experience_max) < parseInt(this.formData.experience_min)) {
                        this.errors.experience_max = 'Maximum experience must be greater than or equal to minimum experience';
                    } else {
                        delete this.errors.experience_max;
                    }
                }
            },
            
            // Dynamic UI Logic (Tasks 23-29)
            toggleDurationField() {
                this.showDurationField = ['Intern', 'Contract'].includes(this.formData.contract_type);
            },
            
            autoFillLocation() {
                // Auto-fill location based on department (Task 24)
                // This is a placeholder - implement based on your business logic
            },
            
            autoExpandTextarea(element) {
                // Auto-expand textarea (Task 27)
                element.style.height = 'auto';
                element.style.height = element.scrollHeight + 'px';
            },
            
            // Autocomplete Methods (Tasks 25, 26, 94)
            async autocompleteJobTitle(query) {
                if (!query || query.length < 2) {
                    this.jobTitleSuggestions = [];
                    return;
                }
                
                try {
                    const response = await fetch(`/{{ $tenantSlug }}/api/requisitions/job-titles?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.jobTitleSuggestions = data.suggestions || [];
                    }
                } catch (error) {
                    console.error('Job title autocomplete error:', error);
                }
            },
            
            selectJobTitle(suggestion) {
                this.formData.job_title = suggestion;
                this.jobTitleSuggestions = [];
                this.showJobTitleSuggestions = false;
                this.updatePreview();
                this.validateField('job_title');
            },
            
            async autocompleteSkills(query) {
                if (!query || query.length < 2) {
                    this.skillSuggestions = [];
                    return;
                }
                
                try {
                    const response = await fetch(`/{{ $tenantSlug }}/api/requisitions/skills?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.skillSuggestions = data.suggestions || [];
                    }
                } catch (error) {
                    console.error('Skills autocomplete error:', error);
                }
            },
            
            selectSkill(suggestion) {
                this.addSkill(suggestion);
                this.skillInput = '';
                this.skillSuggestions = [];
            },
            
            addSkill(skill = null) {
                const skillToAdd = (skill || this.skillInput).trim();
                if (skillToAdd && !this.formData.skills.includes(skillToAdd)) {
                    this.formData.skills.push(skillToAdd);
                    this.skillInput = '';
                    this.updatePreview();
                    this.validateField('skills');
                }
            },
            
            removeSkill(index) {
                this.formData.skills.splice(index, 1);
                this.updatePreview();
                this.validateField('skills');
            },
            
            // File Upload (Task 12, 68, 69)
            handleFileUpload(event) {
                const files = Array.from(event.target.files);
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                files.forEach(file => {
                    // Validate file size (Task 68)
                    if (file.size > maxSize) {
                        this.showToast(`File ${file.name} exceeds 5MB limit`, 'error');
                        return;
                    }
                    
                    // Validate file type (Task 69)
                    if (!allowedTypes.includes(file.type)) {
                        this.showToast(`File ${file.name} is not a valid PDF or DOC file`, 'error');
                        return;
                    }
                    
                    this.uploadedFiles.push({
                        name: file.name,
                        size: file.size,
                        file: file
                    });
                });
            },
            
            removeFile(index) {
                this.uploadedFiles.splice(index, 1);
            },
            
            // Preview Panel (Tasks 56-58)
            updatePreview() {
                // Preview updates automatically via x-text bindings
                this.hasUnsavedChanges = true;
            },
            
            formatBudget() {
                if (this.formData.budget_min && this.formData.budget_max) {
                    return `$${this.formData.budget_min.toLocaleString()} - $${this.formData.budget_max.toLocaleString()}`;
                }
                return 'Not specified';
            },
            
            // Draft Management (Tasks 38-42, 64)
            draftId: null,
            draftSaving: false,
            draftLoaded: false,
            draftLoading: false, // Prevent concurrent load requests
            autosaveTimeout: null,
            
            setupAutosave() {
                // Auto-save every 15 seconds (Task 38) - debounced
                this.autosaveInterval = setInterval(() => {
                    if (this.hasUnsavedChanges && !this.draftSaving) {
                        // Clear any pending autosave
                        if (this.autosaveTimeout) {
                            clearTimeout(this.autosaveTimeout);
                        }
                        // Debounce autosave by 1.5 seconds
                        this.autosaveTimeout = setTimeout(() => {
                            this.saveDraft(true);
                        }, 1500);
                    }
                }, 15000);
            },
            
            async saveDraft(silent = false) {
                // Prevent duplicate requests
                if (this.draftSaving) {
                    if (!silent) {
                        console.log('Draft save already in progress, skipping...');
                    }
                    return;
                }

                this.draftSaving = true;
                
                try {
                    console.log('saveDraft called', {
                        draftId: this.draftId,
                        jobTitle: this.formData.job_title,
                        isSubmitting: this.isSubmitting,
                        silent: silent
                    });
                    
                    const formData = new FormData();
                    Object.keys(this.formData).forEach(key => {
                        if (key === 'skills') {
                            formData.append(key, JSON.stringify(this.formData[key]));
                        } else {
                            formData.append(key, this.formData[key] || '');
                        }
                    });
                    
                    // CRITICAL: If we're submitting the form (creating new requisition), 
                    // DO NOT call saveDraft - it will update existing drafts
                    if (this.isSubmitting) {
                        console.warn('saveDraft called during form submission - ABORTING to prevent updating existing drafts');
                        return;
                    }
                    
                    // Include draft_id if we have one (for updates)
                    if (this.draftId) {
                        formData.append('draft_id', this.draftId);
                    }
                    
                    // If no draft_id, this is a new requisition - don't update existing drafts
                    if (!this.draftId) {
                        formData.append('is_new', 'true');
                    }
                    
                    const response = await fetch(`/{{ $tenantSlug }}/api/requisitions/draft`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        // Store draft_id for future updates
                        if (data.draft_id) {
                            this.draftId = data.draft_id;
                        }
                        
                        this.hasUnsavedChanges = false;
                        if (!silent) {
                            this.showToast('Draft saved successfully', 'success');
                        }
                    } else {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'Failed to save draft');
                    }
                } catch (error) {
                    console.error('Draft save error:', error);
                    if (!silent) {
                        this.showToast('Failed to save draft', 'error');
                    }
                } finally {
                    this.draftSaving = false;
                }
            },
            
            async loadDraft() {
                // Prevent duplicate loads - check both local and global flags
                if (this.draftLoaded || this.draftLoading || window.draftLoadInProgress) {
                    return;
                }

                // Set loading flags immediately to prevent concurrent calls
                this.draftLoading = true;
                window.draftLoadInProgress = true;

                try {
                    const response = await fetch(`/{{ $tenantSlug }}/api/requisitions/draft`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        if (data.draft) {
                            // Store draft_id
                            if (data.draft.id || data.draft.draft_id) {
                                this.draftId = data.draft.id || data.draft.draft_id;
                            }
                            
                            // Check if draft has meaningful data (not just defaults)
                            let hasData = false;
                            const meaningfulFields = ['job_title', 'department', 'justification', 'budget_min', 'budget_max'];
                            meaningfulFields.forEach(field => {
                                if (data.draft[field] && 
                                    data.draft[field] !== 'Draft' && 
                                    data.draft[field] !== 'Draft Requisition' && 
                                    data.draft[field] !== 'TBD' &&
                                    data.draft[field] !== 0) {
                                    hasData = true;
                                }
                            });
                            
                            // Populate form
                            Object.keys(data.draft).forEach(key => {
                                if (key === 'skills') {
                                    try {
                                        let skills = null;
                                        
                                        if (typeof data.draft[key] === 'string') {
                                            // Try to parse as JSON
                                            try {
                                                skills = JSON.parse(data.draft[key]);
                                            } catch (e) {
                                                // If not valid JSON, try to handle as comma-separated string
                                                const skillsString = data.draft[key].trim();
                                                if (skillsString) {
                                                    // Split by comma and clean up
                                                    skills = skillsString.split(',')
                                                        .map(s => s.trim())
                                                        .filter(s => s.length > 0);
                                                }
                                            }
                                        } else if (Array.isArray(data.draft[key])) {
                                            skills = data.draft[key];
                                        }
                                        
                                        if (Array.isArray(skills) && skills.length > 0) {
                                            this.formData[key] = skills;
                                            hasData = true; // Skills count as meaningful data
                                        }
                                    } catch (e) {
                                        console.warn('Failed to parse skills:', e, data.draft[key]);
                                        // Set empty array on error
                                        this.formData[key] = [];
                                    }
                                } else if (key === 'id' || key === 'draft_id') {
                                    // Skip id fields
                                } else if (this.formData.hasOwnProperty(key)) {
                                    this.formData[key] = data.draft[key];
                                }
                            });
                            
                            this.toggleDurationField();
                            this.updatePreview();
                            
                            // Mark as loaded
                            this.draftLoaded = true;
                            
                            // Only show notification if draft has meaningful data (not just defaults)
                            if (hasData) {
                                this.showToast('Draft loaded', 'info');
                            }
                        }
                    }
                } catch (error) {
                    console.error('Draft load error:', error);
                } finally {
                    // Always reset loading flags
                    this.draftLoading = false;
                    window.draftLoadInProgress = false;
                }
            },
            
            // Form Actions
            clearForm() {
                if (confirm('Are you sure you want to clear all form data?')) {
                    this.formData = {
                        department: '',
                        job_title: '',
                        justification: '',
                        budget_min: '',
                        budget_max: '',
                        contract_type: '',
                        duration: '',
                        skills: [],
                        experience_min: '',
                        experience_max: '',
                        headcount: 1,
                        priority: 'Medium',
                        location: '',
                        additional_notes: ''
                    };
                    this.errors = {};
                    this.uploadedFiles = [];
                    this.hasUnsavedChanges = false;
                    this.draftId = null; // Clear draft ID - next save will be new
                    this.draftLoaded = false;
                    this.showToast('Form cleared', 'info');
                }
            },
            
            handleSubmit(event) {
                event.preventDefault();
                
                // Validate form (Task 16)
                if (!this.isFormValid) {
                    // Auto-scroll to first error (Task 21)
                    const firstErrorField = Object.keys(this.errors)[0];
                    if (firstErrorField) {
                        const fieldElement = document.getElementById(firstErrorField);
                        if (fieldElement) {
                            fieldElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            fieldElement.focus();
                        }
                    }
                    this.showToast('Please fix the errors before submitting', 'error');
                    return;
                }
                
                // If saving as draft, submit immediately without confirmation
                if (this.submitAction === 'draft') {
                    this.confirmSubmit();
                    return;
                }
                
                // Show confirmation modal for approval submission (Task 32)
                this.showConfirmModal = true;
            },
            
            async confirmSubmit() {
                this.showConfirmModal = false;
                this.isSubmitting = true;
                
                // Clear unsaved changes flag to prevent beforeunload warning
                this.hasUnsavedChanges = false;
                
                // Stop autosave to prevent updating existing drafts
                if (this.autosaveInterval) {
                    clearInterval(this.autosaveInterval);
                    this.autosaveInterval = null;
                }
                if (this.autosaveTimeout) {
                    clearTimeout(this.autosaveTimeout);
                    this.autosaveTimeout = null;
                }
                
                // Clear draftId to ensure we create a new requisition, not update existing
                const oldDraftId = this.draftId;
                this.draftId = null;
                
                try {
                    const form = document.getElementById('requisition-form');
                    const formData = new FormData(form);
                    
                    // Remove draft_id if it exists - we want to create a new requisition
                    formData.delete('draft_id');
                    
                    // Add skills as JSON
                    formData.set('skills', JSON.stringify(this.formData.skills));
                    
                    // Add submit action (draft or approval)
                    formData.set('submit_action', this.submitAction);
                    
                    // Explicitly set is_new to ensure we create a new requisition
                    formData.set('is_new', 'true');
                    
                    // Add uploaded files
                    this.uploadedFiles.forEach((fileObj, index) => {
                        formData.append(`attachments[${index}]`, fileObj.file);
                    });
                    
                    // Debug: Log form data
                    console.log('Submitting requisition form:', {
                        action: form.action,
                        method: 'POST',
                        skills: JSON.stringify(this.formData.skills),
                        formDataKeys: Array.from(formData.keys())
                    });
                    
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    // Log response for debugging
                    console.log('Response status:', response.status);
                    const responseText = await response.text();
                    console.log('Response text:', responseText);
                    
                    let data;
                    try {
                        data = JSON.parse(responseText);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid response from server');
                    }
                    
                    if (response.ok) {
                        // Clear old draft if we had one (before clearing draftId)
                        if (oldDraftId) {
                            const deleteFormData = new FormData();
                            deleteFormData.append('draft_id', oldDraftId);
                            
                            try {
                                await fetch(`/{{ $tenantSlug }}/api/requisitions/draft`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: deleteFormData
                                });
                            } catch (e) {
                                console.warn('Failed to delete old draft:', e);
                            }
                        }
                        
                        // Reset draft tracking
                        this.draftId = null;
                        this.draftLoaded = false;
                        
                        // Redirect to success page (Task 82)
                        window.location.href = data.redirect_url || '/{{ $tenantSlug }}/requisitions';
                    } else {
                        // Handle validation errors (Task 67)
                        if (data.errors) {
                            this.errors = data.errors;
                            this.showToast('Please fix the errors', 'error');
                        } else {
                            this.showToast(data.message || 'Submission failed', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Submit error:', error);
                    this.showToast('An error occurred. Please try again.', 'error');
                } finally {
                    this.isSubmitting = false;
                }
            },
            
            // Utility Methods
            showTooltip(field) {
                // Tooltip implementation (Task 33)
                const messages = {
                    budget: 'Enter the salary range for this position',
                    headcount: 'Number of positions you need to fill',
                    skills: 'Add relevant skills required for this role'
                };
                alert(messages[field] || 'Help information');
            },
            
            showToast(message, type = 'info') {
                const toast = document.createElement('div');
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };
                
                toast.className = `${colors[type] || colors.info} text-white px-4 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between min-w-[300px]`;
                toast.innerHTML = `
                    <span>${message}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            },
            
            handleBeforeUnload(event) {
                // Unsaved changes warning (Task 93)
                // Don't show warning if form is being submitted
                if (this.hasUnsavedChanges && !this.isSubmitting) {
                    event.preventDefault();
                    event.returnValue = '';
                    return '';
                }
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
