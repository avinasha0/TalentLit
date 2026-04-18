@php
    $chain = $requiredApproverChain ?? [];
@endphp
@if(!empty($chain))
    <x-card>
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-black">Required approvers (full path)</h2>
            <p class="text-sm text-gray-600 mt-1">
                Everyone who may need to act before this requisition is fully approved.
                When several people are listed at the same level, only one approval is required to advance past that level.
            </p>
        </div>
        <div class="px-6 py-6 space-y-6">
            @foreach($chain as $stage)
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Level {{ $stage['level'] }}</h3>
                    <div class="mt-3 space-y-4">
                        @foreach($stage['groups'] as $group)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $group['label'] }}</p>
                                <ul class="mt-2 space-y-1.5">
                                    @forelse($group['users'] as $u)
                                        <li class="text-sm text-gray-900">
                                            {{ $u['name'] }}
                                            @if(!empty($u['email']))
                                                <span class="text-gray-500">· {{ $u['email'] }}</span>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="text-sm text-amber-700">Not configured — assign this role in team settings.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </x-card>
@endif
