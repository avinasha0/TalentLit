@php
    $user = $node['user'];
    $role = $node['role'];
    $level = $node['level'];
    $children = $node['children'] ?? [];
    
    // Role colors - new hierarchy
    $roleColors = [
        'CEO' => 'bg-purple-100 text-purple-800 border-purple-300',
        'Owner' => 'bg-purple-100 text-purple-800 border-purple-300', // Backward compatibility
        'Line Manager' => 'bg-blue-100 text-blue-800 border-blue-300',
        'DepartmentHead' => 'bg-blue-100 text-blue-800 border-blue-300', // Backward compatibility
        'HR Manager' => 'bg-green-100 text-green-800 border-green-300',
        'HRManager' => 'bg-green-100 text-green-800 border-green-300', // Backward compatibility
        'HR Recruiter' => 'bg-orange-100 text-orange-800 border-orange-300',
        'Recruiter' => 'bg-orange-100 text-orange-800 border-orange-300', // Backward compatibility
    ];
    $roleColor = $roleColors[$role] ?? 'bg-gray-100 text-gray-800 border-gray-300';
    
    $hasChildren = !empty($children);
    $childrenCount = count($children);
@endphp

<div class="flex flex-col items-center {{ $isRoot ? '' : 'mt-3' }}">
    <!-- User Card (Leaf) -->
    <div class="relative flex flex-col items-center">
        <!-- Vertical line from parent (if not root) -->
        @if(!$isRoot)
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-1 h-4 bg-gray-500"></div>
        @endif
        
        <!-- User Card -->
        <div class="group relative flex flex-col items-center p-2.5 bg-white border-2 {{ $roleColor }} rounded-lg shadow-md hover:shadow-lg transition-all min-w-[140px] max-w-[160px] cursor-pointer org-level-{{ $level }}">
            <!-- Avatar -->
            <div class="mb-2">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center shadow-sm">
                    <span class="text-sm font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="text-center w-full">
                <p class="text-xs font-semibold text-gray-900 mb-0.5 leading-tight">{{ $user->name }}</p>
                <p class="text-[10px] text-gray-600 mb-1 truncate w-full" title="{{ $user->email }}">{{ $user->email }}</p>
                <span class="inline-block px-2 py-0.5 text-[10px] font-semibold rounded-full border {{ $roleColor }}">
                    {{ $role }}
                </span>
            </div>
            
            <!-- Hover Tooltip -->
            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-3 hidden group-hover:block z-50 w-64 pointer-events-none">
                <div class="bg-gray-900 text-white text-xs rounded-lg shadow-2xl p-4 border border-gray-700">
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                    
                    <div class="space-y-2">
                        <div>
                            <span class="text-gray-400 font-semibold">Employee Name:</span>
                            <p class="text-white font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-400 font-semibold">Email:</span>
                            <p class="text-white">{{ $user->email }}</p>
                        </div>
                        @if(isset($user->phone) && $user->phone)
                        <div>
                            <span class="text-gray-400 font-semibold">Phone:</span>
                            <p class="text-white">{{ $user->phone }}</p>
                        </div>
                        @elseif(isset($user->phone_number) && $user->phone_number)
                        <div>
                            <span class="text-gray-400 font-semibold">Phone:</span>
                            <p class="text-white">{{ $user->phone_number }}</p>
                        </div>
                        @else
                        <div>
                            <span class="text-gray-400 font-semibold">Phone:</span>
                            <p class="text-gray-500">N/A</p>
                        </div>
                        @endif
                        <div>
                            <span class="text-gray-400 font-semibold">Reporting To:</span>
                            @php
                                $reportingTo = 'N/A';
                                if (isset($node['parentName']) && $node['parentName']) {
                                    $reportingTo = $node['parentName'] . ' (' . ($node['parentRole'] ?? '') . ')';
                                } elseif ($level > 1) {
                                    // Fallback to role-based reporting - new hierarchy
                                    if ($role === 'Line Manager' || $role === 'DepartmentHead') {
                                        $reportingTo = 'CEO';
                                    } elseif ($role === 'HR Manager' || $role === 'HRManager') {
                                        $reportingTo = 'Line Manager';
                                    } elseif ($role === 'HR Recruiter' || $role === 'Recruiter') {
                                        $reportingTo = 'HR Manager';
                                    }
                                }
                            @endphp
                            <p class="text-white">{{ $reportingTo }}</p>
                        </div>
                        @if(isset($user->job_openings_count) || isset($user->requisitions_count))
                        <div class="pt-2 border-t border-gray-700 flex items-center justify-between text-[10px]">
                            @if(isset($user->job_openings_count))
                            <span class="text-gray-400">Jobs: <span class="text-white">{{ $user->job_openings_count }}</span></span>
                            @endif
                            @if(isset($user->requisitions_count))
                            <span class="text-gray-400">Requisitions: <span class="text-white">{{ $user->requisitions_count }}</span></span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vertical line to children (if has children) -->
        @if($hasChildren)
        <div class="w-1 h-4 bg-gray-500 mt-1.5"></div>
        @endif
    </div>
    
    <!-- Children (Branches) -->
    @if($hasChildren)
        <div class="relative mt-1.5 flex items-start justify-center" style="gap: 1.5rem;">
            <!-- Horizontal connector line (branch) -->
            @if($childrenCount > 1)
            <div class="absolute top-0 left-0 right-0 h-1 bg-gray-500" style="left: 50%; transform: translateX(-50%); width: calc(100% - 3rem);"></div>
            @endif
            
            @foreach($children as $index => $childNode)
                <div class="relative flex flex-col items-center">
                    <!-- Vertical line from horizontal branch to child -->
                    <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-gray-500"></div>
                    
                    @include('tenant.organization.partials.tree-node', ['node' => $childNode, 'isRoot' => false])
                </div>
            @endforeach
        </div>
    @endif
</div>

