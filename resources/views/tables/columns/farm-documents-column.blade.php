<div class="space-y-2">
    @if($getRecord()->farmerRequirements && $getRecord()->farmerRequirements->count() > 0)
        @foreach ($getRecord()->farmerRequirements as $requirement)
            <div class="text-xs p-0">
                @php
                    $media = $requirement->media->first();
                    $documentUrl = $media ? $media->getUrl() : null;
                @endphp

                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                    <div>
                        <span class="font-medium">{{ $requirement->requirement ? $requirement->requirement->name : 'Document' }}</span>
                        @if($requirement->is_checked)
                            <span class="ml-2 text-xs bg-green-50 text-green-600 px-1.5 py-0.5 rounded-full font-medium">Verified</span>
                        @else
                            <span class="ml-2 text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full font-medium">Pending</span>
                        @endif
                    </div>

                    @if($documentUrl)
                        <a href="{{ $documentUrl }}" target="_blank" class="text-primary-600 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </a>
                    @else
                        <span class="text-gray-500 italic">No document</span>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="text-sm text-gray-500 italic">No requirements found</div>
    @endif
</div>
