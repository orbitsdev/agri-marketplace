<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow rounded-lg max-w-lg p-8">
        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button
                    type="submit"
                    class="text-red-500 hover:underline"
                >
                    Log Out
                </button>
            </form>
        </div>
        <!-- Welcome Message -->
        <h1 class="text-xl font-semibold text-gray-800 mb-4">
            Welcome, {{ Auth::user()->name }}!
        </h1>

        <!-- Status Message with Badge -->
        <div class="flex items-center space-x-2 mb-4">
            <p class="text-gray-600">
                Your farmer account is currently
            </p>
            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                {{ $status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : ($status === 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                {{ $status }}
            </span>
        </div>

        <!-- Meaningful Status Details -->
        <div class="mb-6">
            @if ($status === 'Pending')
                <p class="text-gray-600">
                    Our team is reviewing your account details. You will be notified once your account is approved.
                </p>
            @elseif ($status === 'Rejected')
                <p class="text-gray-600">
                    Unfortunately, your account has been rejected. Please review the remarks below for more details.
                </p>
            @endif
        </div>

        <!-- Remarks Section -->
        @if ($status === 'Rejected' && $remarks)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
                <h2 class="text-sm font-bold text-red-700">Remarks:</h2>
                <p class="text-sm text-red-600">{{ $remarks }}</p>
            </div>
        @endif

        <!-- Farmer Details with Expandable Section -->
        <div>
            <h3>
                <div class="flex items-center justify-between w-full py-6">
                    <button type="button" class="group flex items-center text-left" aria-controls="disclosure-1" aria-expanded="false">
                        <span class="text-sm font-medium text-gray-900">Farm Details</span>

                    </button>

                    <!-- Update Farm Details Button -->
                    @if ($status === 'Pending' || $status === 'Rejected')
                        <div class="ml-4">
                            {{ ($this->updateFarmDetailsAction)(['record' => Auth::user()->id]) }}
                        </div>
                    @endif
                </div>
            </h3>
            <div class="pb-6" id="disclosure-1">
                <ul role="list" class="list-disc space-y-1 pl-5 text-sm/6 text-gray-700 marker:text-gray-300">
                    <li>Farm Name: {{ Auth::user()->farmer->farm_name ?? 'Not Provided' }}</li>
                    <li>Location: {{ Auth::user()->farmer->location ?? 'Not Provided' }}</li>
                    <li>Farm Size: {{ Auth::user()->farmer->farm_size ?? 'Not Provided' }}</li>
                    <li>Contact: {{ Auth::user()->farmer->contact ?? 'Not Provided' }}</li>
                </ul>
            </div>
            <div class="mt-4 space-y-4 text-sm/6 text-gray-500">

                <p>  {{ Auth::user()->farmer->description ?? 'Not Provided' }}</p>

              </div>
        </div>

        <!-- Update Details Button -->

    </div>

    <!-- Footer with Logout -->

    <x-filament-actions::modals />
</div>
