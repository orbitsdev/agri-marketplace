<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th colspan="2" style=" text-align: left; padding: 10px;">
                Farmer Name: {{ $farmer->user->first_name }} {{ $farmer->user->last_name }} |
                Farm Name: {{ $farmer->farm_name }}
            </th>
        </tr>
        <tr>
            <th style=" padding: 5px;">Document Name</th>
            <th style=" padding: 5px;">File URL</th>
        </tr>
    </thead>
    <tbody>
        @if ($farmer->documents->isNotEmpty())
            @foreach ($farmer->documents as $document)
                <tr>
                    <td align="left" width="60">
                        @if ($document->getFirstMediaUrl())
                        {{ $document->name }}

                        @else
                            No File
                        @endif
                    </td>
                    <td align="left" width="80">
                        @if ($document->getFirstMediaUrl())
                            =HYPERLINK("{{ $document->getFirstMediaUrl() }}", "View File")
                        @else
                            No File
                        @endif
                    </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2" style="padding: 5px; text-align: center;">No Documents Found</td>
            </tr>
        @endif
    </tbody>
</table>
