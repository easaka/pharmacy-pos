<x-layout title="Add User">
<div class="max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-4">Add New User</h2>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <label class="block mb-2">Name <span class="text-red-500">*</span></label>
        <input name="name" value="{{ old('name') }}" required class="w-full p-2 border rounded mb-4" />
        @error('name')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <label class="block mb-2">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border rounded mb-4" />
        @error('email')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <label class="block mb-2">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" required class="w-full p-2 border rounded mb-4" />
        @error('password')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <label class="block mb-2">Confirm Password <span class="text-red-500">*</span></label>
        <input type="password" name="password_confirmation" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2">Role <span class="text-red-500">*</span></label>
        <select name="role" required class="w-full p-2 border rounded mb-4">
            <option value="">-- Select Role --</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="pharmacist" {{ old('role') === 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
            <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
        </select>
        @error('role')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
        @enderror

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create User</button>
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>

</div>
</x-layout>
