<x-layout title="Role Permissions">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Role Permissions</h1>
                <p class="mt-1 text-gray-600">Manage default permissions for each role</p>
            </div>
            <a href="{{ route('users.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($roles as $role)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-900 capitalize">{{ $role }} Role</h2>
                    <p class="text-sm text-gray-600 mt-1">Set default permissions for all users with the {{ $role }} role</p>
                </div>

                <form action="{{ route('permissions.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($allPermissions as $permission)
                            <div class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       id="role_{{ $role }}_{{ $permission->id }}"
                                       value="{{ $permission->id }}"
                                       {{ in_array($permission->id, $rolePermissions[$role] ?? []) ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="ml-3 flex-1">
                                    <label for="role_{{ $role }}_{{ $permission->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                        {{ $permission->name }}
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">{{ $permission->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <i class="fas fa-save mr-2"></i> Update {{ ucfirst($role) }} Permissions
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</x-layout>
