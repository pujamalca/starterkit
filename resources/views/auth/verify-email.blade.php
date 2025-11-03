<x-layouts.guest>
    <x-slot name="title">Email Verification</x-slot>

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
        <p class="mt-2 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                A new verification link has been sent to the email address you provided during registration.
            </p>
        </div>
    @endif

    <div class="flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Resend Verification Email
            </button>
        </form>

        <div class="text-center">
            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                Back to Home
            </a>
        </div>
    </div>
</x-layouts.guest>
