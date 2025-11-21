<x-layout title="User Details">
<div class="max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">User Details</h2>

    <div class="bg-white p-6 rounded shadow">
        <div class="mb-4">
            <label class="font-semibold">Name:</label>
            <p class="mt-1">{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Email:</label>
            <p class="mt-1">{{ $user->email }}</p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Role:</label>
            <p class="mt-1">
                <span class="px-2 py-1 rounded text-xs font-semibold
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $user->role === 'pharmacist' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $user->role === 'cashier' ? 'bg-green-100 text-green-800' : '' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Created At:</label>
            <p class="mt-1">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Last Updated:</label>
            <p class="mt-1">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>

        <div class="flex gap-2 mt-6">
            <a href="{{ route('users.edit', $user) }}" class="bg-yellow-600 text-white px-4 py-2 rounded">Edit User</a>
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back to List</a>
        </div>
    </div>

</div>
</x-layout>
