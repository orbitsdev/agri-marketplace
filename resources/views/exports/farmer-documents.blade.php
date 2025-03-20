<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
         
            <th>Document Name</th>
            <th>Document URL</th>
        </tr>
    </thead>
    <tbody>
        @if ($farmer->documents->isNotEmpty())
            @foreach ($farmer->documents as $index => $document)
                <tr>

                    <td>{{ $document->getFirstMedia('file')?->name ?? 'No File' }}</td>
                    <td>
                        @if ($document->getFirstMedia('file'))
                            <a href="{{ $document->getFirstMediaUrl('file') }}" target="_blank">
                                {{ $document->getFirstMedia('file')?->file_name }}
                            </a>
                        @else
                            No File
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No Documents Found</td>
            </tr>
        @endif
    </tbody>
</table>
