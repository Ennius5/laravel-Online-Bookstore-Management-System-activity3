@extends('layouts.app')

@section('title', 'Two-Factor Authentication - PageTurner')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-transparent">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-6 text-center">
            <svg class="w-16 h-16 text-amber-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h2>
            <p class="text-gray-600 mt-2">
                Please enter the verification code sent to your email.
            </p>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.verify') }}">
            @csrf

            <!-- Verification Code -->
            <div>
                <label for="code" class="block font-medium text-sm text-black mb-1">
                    Verification Code
                </label>
                <input id="code"
                       type="text"
                       name="code"
                       required
                       autofocus
                       placeholder="Enter 6-digit code"
                       maxlength="6"
                       pattern="\d{6}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-black">
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember this device -->
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input id="remember"
                           type="checkbox"
                           name="remember"
                           value="1"
                           class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500">
                    <span class="ml-2 text-sm text-gray-600">
                        Trust this device for 30 days
                    </span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit"
                        class="w-full bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition font-medium">
                    Verify Code
                </button>
            </div>

            <div class="text-center mt-4">
                <button type="button"
                        onclick="resendCode()"
                        class="text-sm text-amber-600 hover:text-amber-800 font-medium">
                    Didn't receive code? Resend
                </button>
            </div>
        </form>

        <!-- Resend Code Form -->
        <form id="resend-form" method="POST" action="{{ route('two-factor.resend') }}" class="hidden">
            @csrf
        </form>
    </div>
</div>

@push('scripts')
<script>
function resendCode() {
    if (confirm('Resend verification code to your email?')) {
        document.getElementById('resend-form').submit();
    }
}
</script>
@endpush
@endsection
