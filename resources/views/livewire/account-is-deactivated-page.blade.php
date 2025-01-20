<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow rounded-lg max-w-lg p-8">
        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-500 hover:underline">
                    Log Out
                </button>
            </form>
        </div>

        <!-- Warning Message -->
        <div class="flex flex-col items-center text-center space-y-4">
            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-red-600">Account Deactivated</h1>
            <p class="text-gray-600">
                Your account has been deactivated. Please contact the administrator to resolve this issue.
            </p>
        </div>
    </div>
</div>
