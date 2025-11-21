<x-layout title="User Permissions">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Permissions</h1>
                <p class="mt-1 text-gray-600">Set permissions for {{ $user->name }} ({{ ucfirst($user->role) }})</p>
            </div>
            <a href="{{ route('users.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
        <form action="{{ route('users.permissions.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-4">
                    Select the permissions to grant to this user. Changes will apply to all users with the <strong>{{ ucfirst($user->role) }}</strong> role.
                </p>
            </div>

            <div class="space-y-4">
                @foreach($allPermissions as $permission)
                    <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <input type="checkbox" 
                               name="permissions[]" 
                               id="permission_{{ $permission->id }}"
                               value="{{ $permission->id }}"
                               {{ in_array($permission->slug, $userPermissions) ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <div class="ml-3 flex-1">
                            <label for="permission_{{ $permission->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                {{ $permission->name }}
                            </label>
                            <p class="text-sm text-gray-500 mt-1">{{ $permission->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-save mr-2"></i> Save Permissions
                </button>
            </div>
        </form>
    </div>
</x-layout>
