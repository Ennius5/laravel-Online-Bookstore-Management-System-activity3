@extends('layouts.app')

@section('title', 'Two-Factor Authentication - PageTurner')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Two-Factor Authentication</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Add additional security to your account using two-factor authentication.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(auth()->user()->two_factor_enabled)
                        <div class="mb-6">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h4 class="text-lg font-medium text-gray-900">2FA is currently <span class="text-green-600">ENABLED</span></h4>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                You will need to enter a verification code from your email each time you log in.
                            </p>

                            <!-- Recovery Codes Section -->
                            @if(session('recovery_codes'))
                                <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                                    <h5 class="font-medium text-yellow-800 mb-2">Your Recovery Codes</h5>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        Store these codes in a safe place. Each code can only be used once.
                                    </p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(session('recovery_codes') as $code)
                                            <code class="block p-2 bg-white rounded border border-yellow-300 text-center font-mono text-sm">
                                                {{ $code }}
                                            </code>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-yellow-600 mt-2">
                                        These codes will only be shown once. Copy them now!
                                    </p>
                                </div>
                            @endif

                            <!-- Disable 2FA Form -->
                            <form method="POST" action="{{ route('profile.two-factor.disable') }}" class="mt-6">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to disable two-factor authentication?')"
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Disable Two-Factor Authentication
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <h4 class="text-lg font-medium text-gray-900">2FA is currently <span class="text-gray-500">DISABLED</span></h4>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                Enhance your account security by enabling two-factor authentication.
                            </p>

                            <!-- Enable 2FA Form -->
                            <form method="POST" action="{{ route('profile.two-factor.enable') }}" class="mt-6">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    Enable Two-Factor Authentication
                                </button>
                            </form>
                        </div>
                    @endif

                    <hr class="my-6">

                    <div class="text-sm text-gray-600">
                        <h5 class="font-medium text-gray-900 mb-2">How it works:</h5>
                        <ul class="list-disc list-inside space-y-1">
                            <li>When enabled, you'll receive a verification code via email after logging in</li>
                            <li>Codes expire after 10 minutes</li>
                            <li>You can "trust" devices to remember them for 30 days</li>
                            <li>Save your recovery codes in case you lose access to email</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
