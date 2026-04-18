@php
    $user = $node['user'];
    $role = $node['role'];
    $level = $node['level'];
    $children = $node['children'] ?? [];

    // Role colors - new hierarchy
    $roleColors = [
        'CEO' => 'border-violet-200 bg-violet-50 text-violet-700',
        'Owner' => 'border-violet-200 bg-violet-50 text-violet-700',
        'Line Manager' => 'border-blue-200 bg-blue-50 text-blue-700',
        'DepartmentHead' => 'border-blue-200 bg-blue-50 text-blue-700',
        'HR Manager' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'HRManager' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'HR Recruiter' => 'border-amber-200 bg-amber-50 text-amber-700',
        'Recruiter' => 'border-amber-200 bg-amber-50 text-amber-700',
    ];
    $roleColor = $roleColors[$role] ?? 'border-gray-200 bg-gray-50 text-gray-700';

    $cardRingByLevel = [
        1 => 'ring-violet-100',
        2 => 'ring-blue-100',
        3 => 'ring-emerald-100',
        4 => 'ring-amber-100',
    ];
    $cardRing = $cardRingByLevel[$level] ?? 'ring-gray-100';

    $hasChildren = !empty($children);
    $childrenCount = count($children);
@endphp

<div class="flex flex-col items-center {{ $isRoot ? '' : 'mt-4' }}">
    <div class="relative flex flex-col items-center">
        @if(!$isRoot)
        <div class="absolute -top-4 left-1/2 h-4 w-px -translate-x-1/2 bg-gray-300"></div>
        @endif

        <div class="group relative w-[188px] rounded-xl border border-gray-200 bg-white p-3 shadow-sm ring-1 {{ $cardRing }} transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
            <div class="mb-2 flex items-center justify-between">
                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $roleColor }}">
                    {{ $role }}
                </span>
                <span class="text-[10px] font-medium uppercase tracking-wide text-gray-400">L{{ $level }}</span>
            </div>

            <div class="flex items-center gap-3">
                <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-violet-500 to-blue-500 flex items-center justify-center shadow-sm">
                    <span class="text-xs font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-900" title="{{ $user->name }}">{{ $user->name }}</p>
                    <p class="truncate text-xs text-gray-500" title="{{ $user->email }}">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mt-3 border-t border-gray-100 pt-2 text-[11px] text-gray-500">
                @php
                    $reportingTo = 'Top Level';
                    if (isset($node['parentName']) && $node['parentName']) {
                        $reportingTo = $node['parentName'];
                    }
                @endphp
                <div class="flex items-center justify-between">
                    <span>Reports To</span>
                    <span class="max-w-[95px] truncate font-medium text-gray-700" title="{{ $reportingTo }}">{{ $reportingTo }}</span>
                </div>
                <div class="mt-1 flex items-center justify-between">
                    <span>Openings</span>
                    <span class="font-medium text-gray-700">{{ $user->job_openings_count ?? 0 }}</span>
                </div>
            </div>

            <div class="pointer-events-none absolute left-1/2 top-0 z-50 hidden w-72 -translate-x-1/2 -translate-y-[105%] group-hover:block">
                <div class="rounded-lg border border-gray-700 bg-gray-900 p-3 text-xs text-white shadow-2xl">
                    <div class="space-y-1.5">
                        <p><span class="font-semibold text-gray-300">Employee:</span> {{ $user->name }}</p>
                        <p><span class="font-semibold text-gray-300">Role:</span> {{ $role }}</p>
                        <p><span class="font-semibold text-gray-300">Email:</span> {{ $user->email }}</p>
                        @if(isset($user->requisitions_count))
                            <p><span class="font-semibold text-gray-300">Requisitions:</span> {{ $user->requisitions_count }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($hasChildren)
        <div class="mt-2 h-5 w-px bg-gray-300"></div>
        @endif
    </div>

    @if($hasChildren)
        <div class="relative mt-1.5 flex items-start justify-center gap-5">
            @if($childrenCount > 1)
            <div class="absolute top-0 left-1/2 h-px -translate-x-1/2 bg-gray-300" style="width: calc(100% - 2.8rem);"></div>
            @endif

            @foreach($children as $childNode)
                <div class="relative flex flex-col items-center">
                    <div class="absolute -top-1 left-1/2 h-1 w-px -translate-x-1/2 bg-gray-300"></div>
                    @include('tenant.organization.partials.tree-node', ['node' => $childNode, 'isRoot' => false])
                </div>
            @endforeach
        </div>
    @endif
</div>

