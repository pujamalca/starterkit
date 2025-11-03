<x-layouts.guest>
    <x-slot name="title">Forgot Password</x-slot>

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Forgot Your Password?</h2>
        <p class="mt-2 text-sm text-gray-600">
            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                {{ session('status') }}
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Email Password Reset Link
            </button>
        </div>

        <div class="mt-4 text-center space-y-2">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 underline font-medium">
                    Register here
                </a>
            </p>
            <p class="text-sm text-gray-600">
                <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-900 underline">
                    Back to Home
                </a>
            </p>
        </div>
    </form>
</x-layouts.guest>
