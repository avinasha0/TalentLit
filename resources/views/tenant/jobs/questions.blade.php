@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
        ['label' => $job->title, 'url' => route('tenant.jobs.show', [$tenant->slug, $job->id])],
        ['label' => 'Questions', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-black">Job Application Questions</h1>
                <p class="mt-1 text-sm text-black">Configure custom questions for "{{ $job->title }}"</p>
            </div>
        </div>

        <!-- Questions Management -->
        <x-card>
            <form method="POST" action="{{ route('tenant.jobs.questions.update', [$tenant->slug, $job->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Available Questions -->
                    <div>
                        <h3 class="text-lg font-medium text-black mb-4">Available Questions</h3>
                        <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4">
                            @forelse($availableQuestions as $question)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm text-black">{{ $question->label }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</div>
                                    </div>
                                    <button type="button" 
                                            onclick="addQuestion('{{ $question->id }}', '{{ $question->label }}', '{{ $question->type }}', {{ $question->required ? 'true' : 'false' }})"
                                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Add
                                    </button>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No questions available. Create some questions first.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Selected Questions -->
                    <div>
                        <h3 class="text-lg font-medium text-black mb-4">Selected Questions</h3>
                        <div id="selected-questions" class="space-y-2 min-h-32 border border-gray-200 rounded-md p-4">
                            @foreach($job->applicationQuestions as $index => $question)
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-md border border-blue-200" data-question-id="{{ $question->id }}">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm text-black">{{ $question->label }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="questions[{{ $index }}][required]" 
                                                   value="1"
                                                   {{ $question->pivot->required_override ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-1 text-xs text-gray-600">Required</span>
                                        </label>
                                        <button type="button" 
                                                onclick="removeQuestion(this)"
                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                            Remove
                                        </button>
                                    </div>
                                    <input type="hidden" name="questions[{{ $index }}][question_id]" value="{{ $question->id }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Questions
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        let questionIndex = {{ $job->applicationQuestions->count() }};

        function addQuestion(id, label, type, required) {
            const container = document.getElementById('selected-questions');
            
            // Check if question already exists
            if (document.querySelector(`[data-question-id="${id}"]`)) {
                return;
            }

            const questionDiv = document.createElement('div');
            questionDiv.className = 'flex items-center justify-between p-3 bg-blue-50 rounded-md border border-blue-200';
            questionDiv.setAttribute('data-question-id', id);
            
            questionDiv.innerHTML = `
                <div class="flex-1">
                    <div class="font-medium text-sm text-black">${label}</div>
                    <div class="text-xs text-gray-500">${type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</div>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="questions[${questionIndex}][required]" 
                               value="1"
                               ${required ? 'checked' : ''}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-1 text-xs text-gray-600">Required</span>
                    </label>
                    <button type="button" 
                            onclick="removeQuestion(this)"
                            class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                        Remove
                    </button>
                </div>
                <input type="hidden" name="questions[${questionIndex}][question_id]" value="${id}">
            `;
            
            container.appendChild(questionDiv);
            questionIndex++;
        }

        function removeQuestion(button) {
            button.closest('[data-question-id]').remove();
        }
    </script>
</x-app-layout>
