<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Settings', 'url' => null],
                ['label' => 'Team Management', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Team Management</h1>
                <p class="mt-1 text-sm text-white">
                    Manage your team members and their roles within the organization.
                </p>
                <div class="mt-2 p-3 bg-blue-500/20 border border-blue-400/30 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-300 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-100">
                            <strong>Important:</strong> Your organization must have at least one Owner. The last Owner cannot be removed or have their role changed.
                        </div>
                    </div>
                </div>
                @if($maxUsers !== -1)
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="text-xs text-white/80">
                            {{ $currentUserCount }} / {{ $maxUsers }} users
                        </span>
                        <div class="w-20 bg-white/20 rounded-full h-1.5">
                            <div class="bg-white h-1.5 rounded-full transition-all duration-300" 
                                 style="width: {{ $maxUsers > 0 ? min(100, ($currentUserCount / $maxUsers) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
                    @if($canAddUsers)
                        <button onclick="openCreateUserModal()"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Team Member
                        </button>
                    @else
                        <button onclick="showUserLimitReached()"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-gray-400 to-gray-500 cursor-not-allowed opacity-75">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Team Member
                        </button>
                    @endif
        </div>

        <!-- Team Members List -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-black">Team Members</h3>
            </x-slot>

            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $userRole = $user->tenant_roles->first();
                                            $roleColors = [
                                                'Owner' => 'bg-purple-100 text-purple-800',
                                                'Admin' => 'bg-blue-100 text-blue-800',
                                                'Recruiter' => 'bg-green-100 text-green-800',
                                                'Hiring Manager' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                            
                                            // Safe access to role name
                                            $roleName = null;
                                            if ($userRole && is_object($userRole) && isset($userRole->name)) {
                                                $roleName = $userRole->name;
                                            }
                                            
                                            $roleColor = $roleColors[$roleName ?? ''] ?? 'bg-gray-100 text-gray-800';
                                            $userCustomRole = \DB::table('custom_user_roles')
                                                ->where('user_id', $user->id)
                                                ->where('tenant_id', $tenantModel->id)
                                                ->value('role_name');
                                            
                                            // Count owners by checking each user's tenant_roles
                                            $ownerCount = 0;
                                            foreach ($users as $u) {
                                                if ($u->tenant_roles->isNotEmpty() && $u->tenant_roles->first()->name === 'Owner') {
                                                    $ownerCount++;
                                                }
                                            }
                                            $isLastOwner = $userCustomRole === 'Owner' && $ownerCount <= 1;
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                                                {{ $roleName ?? 'No Role' }}
                                            </span>
                                            @if($isLastOwner)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800" 
                                                      title="This is the last owner. Cannot be removed or have role changed.">
                                                    Last Owner
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $roleName ?? '' }}', {{ $userCustomRole === 'Owner' ? 'true' : 'false' }})" 
                                                class="text-blue-600 hover:text-blue-900">Edit</button>
                                        <button onclick="resendInvitation({{ $user->id }})" 
                                                class="text-green-600 hover:text-green-900">Resend Invite</button>
                                        @if($userCustomRole !== 'Owner' || $ownerCount > 1)
                                            <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}', {{ $userCustomRole === 'Owner' ? 'true' : 'false' }})" 
                                                    class="text-red-600 hover:text-red-900">Remove</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @else
                <x-empty 
                    icon="user-group"
                    title="No team members"
                    description="Add your first team member to get started."
                    actionText="Add Team Member"
                    onclick="{{ $canAddUsers ? 'openCreateUserModal()' : 'showUserLimitReached()' }}"
                />
            @endif
        </x-card>
    </div>

    <!-- Create User Modal -->
    <div id="create-user-modal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 ease-in-out">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 ease-in-out scale-95 opacity-0" id="create-user-modal-content">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Add Team Member</h3>
                                <p class="text-sm text-gray-500">Invite a new member to your team</p>
                            </div>
                        </div>
                        <button onclick="closeCreateUserModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <form action="{{ route('tenant.users.store', $tenantModel->slug) }}" method="POST" class="px-6 py-6">
                    @csrf
                    
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="space-y-6">
                        <!-- Full Name Field -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required
                                       placeholder="Enter full name"
                                       value="{{ old('name') }}"
                                       class="block w-full pl-10 pr-3 py-3 border {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg shadow-sm placeholder-gray-400 focus:outline-none transition-colors duration-200">
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required
                                       placeholder="Enter email address"
                                       value="{{ old('email') }}"
                                       class="block w-full pl-10 pr-3 py-3 border {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg shadow-sm placeholder-gray-400 focus:outline-none transition-colors duration-200">
                            </div>
                        </div>

                        <!-- Role Field -->
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <select name="role" 
                                        id="role" 
                                        required
                                        class="block w-full pl-10 pr-10 py-3 border {{ $errors->has('role') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg shadow-sm focus:outline-none transition-colors duration-200 appearance-none bg-white">
                                    <option value="">Select a role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Send Invitation Checkbox -->
                        <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="send_invitation" 
                                       id="send_invitation" 
                                       value="1"
                                       {{ old('send_invitation', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200">
                            </div>
                            <div class="text-sm">
                                <label for="send_invitation" class="font-medium text-blue-900">
                                    Send invitation email
                                </label>
                                <p class="text-blue-700 mt-1">
                                    The new member will receive an email with login instructions and their assigned role details.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-8 flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="closeCreateUserModal()"
                                class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Member
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-black">Edit Team Member</h3>
                    <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="edit-user-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-black">Full Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="edit_name" 
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-black">Email Address</label>
                            <input type="email" 
                                   name="email" 
                                   id="edit_email" 
                                   required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="edit_role" class="block text-sm font-medium text-black">Role</label>
                            <select name="role" 
                                    id="edit_role" 
                                    required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeEditUserModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for modal */
        .modal-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .modal-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .modal-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .modal-scroll::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Focus styles for better accessibility */
        .focus-ring:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
    </style>

    <script>
        function openCreateUserModal() {
            const modal = document.getElementById('create-user-modal');
            const modalContent = document.getElementById('create-user-modal-content');
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Focus on first input
            setTimeout(() => {
                document.getElementById('name').focus();
            }, 350);
        }

        function closeCreateUserModal() {
            const modal = document.getElementById('create-user-modal');
            const modalContent = document.getElementById('create-user-modal-content');
            
            // Trigger close animation
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('create-user-modal');
            const modalContent = document.getElementById('create-user-modal-content');
            
            if (event.target === modal) {
                closeCreateUserModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('create-user-modal');
                if (!modal.classList.contains('hidden')) {
                    closeCreateUserModal();
                }
            }
        });

        function openEditUserModal(userId, name, email, roleId, isOwner = false) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = roleId;
            document.getElementById('edit-user-form').action = `/{{ $tenantModel->slug }}/users/${userId}`;
            
            // Check if this is the last owner
            const ownerCount = {{ $users->where('tenant_roles.0.name', 'Owner')->count() }};
            const roleSelect = document.getElementById('edit_role');
            
            if (isOwner && ownerCount <= 1) {
                // Disable role selection for the last owner
                roleSelect.disabled = true;
                roleSelect.title = 'Cannot change role of the last owner';
            } else {
                roleSelect.disabled = false;
                roleSelect.title = '';
            }
            
            document.getElementById('edit-user-modal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('edit-user-modal').classList.add('hidden');
        }

        function resendInvitation(userId) {
            if (confirm('Send invitation email to this user?')) {
                fetch(`/{{ $tenantModel->slug }}/users/${userId}/resend-invitation`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Invitation sent successfully!');
                    } else {
                        alert('Failed to send invitation: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred: ' + error.message);
                });
            }
        }

        function confirmDelete(userId, userName, isOwner = false) {
            // Check if this is the last owner
            const ownerCount = {{ $users->where('tenant_roles.0.name', 'Owner')->count() }};
            
            if (isOwner && ownerCount <= 1) {
                alert('Cannot remove the last owner of the organization. There must be at least one owner.');
                return;
            }
            
            if (confirm(`Are you sure you want to remove ${userName} from the team?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/{{ $tenantModel->slug }}/users/${userId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showUserLimitReached() {
            const currentCount = {{ $currentUserCount }};
            const maxUsers = {{ $maxUsers === -1 ? 'Infinity' : $maxUsers }};
            
            let message = `You've reached the user limit for your current plan. `;
            if (maxUsers !== Infinity) {
                message += `You currently have ${currentCount} users out of ${maxUsers} allowed. `;
            }
            message += `Please upgrade your subscription plan to add more team members.`;
            
            alert(message);
            
            // Redirect to tenant-specific subscription page
            if (confirm('Would you like to view available plans?')) {
                window.location.href = '{{ route("subscription.show", $tenantModel->slug) }}';
            }
        }
    </script>
</x-app-layout>
