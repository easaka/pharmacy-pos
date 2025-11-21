<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pharmacy Inventory Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                <i class="fas fa-pills text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Pharmacy IMS</h1>
            <p class="mt-2 text-gray-600">Inventory Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                        <div>
                            <p class="font-medium mb-1">Please correct the following errors:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           required 
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror" 
                           placeholder="Enter your email" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror" 
                           placeholder="Enter your password" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>

            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="{{ route('users.create') }}" 
                       class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-user-plus mr-1"></i> Add New User
                    </a>
                </div>
            @else
                <p class="mt-6 text-center text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i> Only administrators can create new user accounts.
                </p>
            @endif
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-sm text-gray-600">
            &copy; {{ date('Y') }} Pharmacy Inventory Management System. All rights reserved.
        </p>
    </div>
</body>
</html>
