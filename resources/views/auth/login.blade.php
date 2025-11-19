<x-layout title="Login">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-10">

    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    @if (session('error'))
        <p class="bg-red-100 text-red-700 p-2 mb-3 rounded">
            {{ session('error') }}
        </p>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <label class="block mb-2 font-semibold">Email</label>
        <input type="email" name="email"
            class="w-full p-2 border rounded mb-4"
            required />

        <label class="block mb-2 font-semibold">Password</label>
        <input type="password" name="password"
            class="w-full p-2 border rounded mb-4"
            required />

        <button 
            class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>

    <p class="text-center mt-4">
        Don’t have an account?
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Register</a>
    </p>

</div>

</x-layout>
