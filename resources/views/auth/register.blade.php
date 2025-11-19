<x-layout title="Register">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">

    <h2 class="text-2xl font-bold mb-4 text-center">Create Account</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <label class="block mb-2 font-semibold">Name</label>
        <input type="text" name="name"
               class="w-full p-2 border rounded mb-4"
               required />

        <label class="block mb-2 font-semibold">Email</label>
        <input type="email" name="email"
               class="w-full p-2 border rounded mb-4"
               required />

        <label class="block mb-2 font-semibold">Password</label>
        <input type="password" name="password"
               class="w-full p-2 border rounded mb-4"
               required />

        <label class="block mb-2 font-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation"
               class="w-full p-2 border rounded mb-4"
               required />

        <button 
            class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700">
            Register
        </button>
    </form>

    <p class="text-center mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Login</a>
    </p>

</div>

</x-layout>
