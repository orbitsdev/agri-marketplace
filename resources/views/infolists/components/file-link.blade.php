<div {{ $attributes }} class="text-xs p-0">
   
    @php
        $record = $getRecord();
        $requirement = $record->requirement;
        $media = $record->media->first();
        $documentUrl = $media ? $media->getUrl() : null;
    @endphp

    <div class="flex items-center justify-between">
        <div>
            <span class="font-medium">{{ $requirement ? $requirement->name : 'Document' }}</span>
            @if($record->is_checked)
                <span class="ml-2 text-xs bg-primary-50 text-primary-600 font-medium">- Approved</span>
            @else
            <span class="ml-2 text-xs text-gray-200 font-medium">- Not Approved</span>
            @endif
        </div>

        @if($documentUrl)
            <a href="{{ $documentUrl }}" target="_blank" class="text-primary-600 hover:underline">
                View Document
            </a>
        @else
            <span class="text-gray-500 italic">No document uploaded</span>
        @endif
    </div>
</div>
