<div class="min-h-screen flex items-center justify-center bg-gray-100 px-8">
    <div class="bg-white shadow rounded-lg  p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                @if ($status === 'Rejected' || $status === 'Draft')
                    {{ ($this->updateFarmDetailsAction)(['record' => Auth::user()->id]) }}
                @elseif ($status === 'Pending')
                    {{ $this->cancelApplicationAction }}
                @endif
            </div>
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
            Welcome, {{ Auth::user()->fullName }}!
        </h1>

        <!-- Status Message with Badge -->
        <div class="flex items-center space-x-2 mb-4">
            <p class="text-gray-600">
                Your farmer account is currently
            </p>
            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                {{ $status === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                   ($status === 'Rejected' ? 'bg-red-100 text-red-700' :
                   ($status === 'Blocked' ? 'bg-gray-100 text-gray-800' :
                   ($status === 'Draft' ? 'bg-blue-100 text-blue-700' : 'bg-blue-100 text-blue-700'))) }}">
                {{ $status }}
            </span>
        </div>

        <!-- Status Handling -->
        <div class="mb-6">
            @if ($status === 'Draft')
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Complete Your Registration</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Your account is in draft mode. Please follow these steps to complete your registration:</p>
                                <ol class="list-decimal pl-5 mt-2 space-y-1">
                                    <li>Fill in all required farm details</li>
                                    <li>Upload all necessary documents</li>
                                    <li>Contact an administrator when you're ready for review</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($status === 'Pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Application Under Review</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Our team is reviewing your account details. You will be notified once your application is approved.</p>
                                <p class="mt-2">If you need to make changes to your application, you can cancel it and return to draft status.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($status === 'Rejected')
                <p class="text-gray-600">
                    Unfortunately, your account has been rejected. Please review the remarks below for more details.
                </p>
            @elseif ($status === 'Blocked')
                <div class="flex flex-col items-center text-center space-y-4">
                    <!-- Emphasized Icon -->
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-red-100">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                          </svg>

                    </div>
                    <p class="text-gray-600">
                        Your account has been <span class="font-bold text-red-600">blocked</span>, and you are unable to access the dashboard or any functionalities. Please contact support for further assistance.
                    </p>
                </div>
            @endif
        </div>

        <!-- Remarks Section -->
        @if ($status === 'Rejected' && $remarks)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
                <h2 class="text-sm font-bold text-red-700">Remarks:</h2>
                <p class="text-sm text-red-600">{{ $remarks }}</p>
            </div>
        @endif

        <!-- Two-column layout for Farm Details and Required Documents -->
        @if ($status !== 'Blocked')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Farm Details -->
                <div>
                    <div class="w-full py-4">
                        <h3 class="text-sm font-medium text-gray-900">Farm Details</h3>
                    </div>

                    <div class="border rounded-md p-4 bg-gray-50">
                        <ul role="list" class="space-y-3 text-sm/6 text-gray-700">
                            <li class="flex items-start">
                                <span class="font-medium w-24">Farm Name:</span>
                                <span>{{ Auth::user()->farmer->farm_name ?? 'Not Provided' }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="font-medium w-24">Location:</span>
                                <span>{{ Auth::user()->farmer->location ?? 'Not Provided' }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="font-medium w-24">Farm Size:</span>
                                <span>{{ Auth::user()->farmer->farm_size ?? 'Not Provided' }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="font-medium w-24">Contact:</span>
                                <span>{{ Auth::user()->farmer->contact ?? 'Not Provided' }}</span>
                            </li>
                        </ul>

                        @if(Auth::user()->farmer->description)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-xs font-medium text-gray-700 mb-2">Description</h4>
                                <p class="text-sm text-gray-600">{{ Auth::user()->farmer->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Required Documents -->
                <div>
                    <h3 class="text-sm font-medium text-gray-900 py-4">Required Documents</h3>

                    @php
                        $farmerRequirements = Auth::user()->farmer->farmerRequirements()->with(['requirement', 'media'])->get();
                    @endphp

                    @if($farmerRequirements->count() > 0)
                        <div class="space-y-3 border rounded-md p-4 bg-gray-50">
                            @foreach($farmerRequirements as $requirement)
                                <div class="border-b border-gray-200 pb-3 mb-3 last:border-b-0 last:pb-0 last:mb-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-medium">{{ $requirement->requirement->name }}</span>
                                            @if($requirement->is_checked)
                                                <span class="ml-2 text-xs bg-green-50 text-green-600 font-medium px-2 py-1 rounded">Approved</span>
                                            @else
                                                <span class="ml-2 text-xs text-gray-500 font-medium">Not Approved</span>
                                            @endif
                                        </div>

                                        @php
                                            $media = $requirement->media->first();
                                            $documentUrl = $media ? $media->getUrl() : null;
                                        @endphp

                                        @if($documentUrl)
                                            <a href="{{ $documentUrl }}" target="_blank" class="text-primary-600 hover:underline text-sm flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                                View Document
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic text-sm">No document uploaded</span>
                                        @endif
                                    </div>

                                    @if($requirement->requirement->description)
                                        <p class="text-xs text-gray-500 mt-2">{{ $requirement->requirement->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border rounded-md p-4 bg-gray-50">
                            <p class="text-gray-500 italic">No requirements found.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <x-filament-actions::modals />
</div>
