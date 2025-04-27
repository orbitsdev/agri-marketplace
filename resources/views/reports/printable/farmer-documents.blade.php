@extends('reports.printable.layout')

@section('content')
<h2>Farmer Documents Report</h2>

<div class="farmer-info" style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #106c3b;">
    <p><strong>Farmer Name:</strong> {{ $farmer->user->first_name }} {{ $farmer->user->last_name }}</p>
    <p><strong>Farm Name:</strong> {{ $farmer->farm_name }}</p>
    <p><strong>Contact:</strong> {{ $farmer->user->email }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Document Name</th>
            <th>Status</th>
            <th>Date Uploaded</th>
        </tr>
    </thead>
    <tbody>
        @if ($farmer->documents->isNotEmpty())
            @foreach ($farmer->documents as $document)
                <tr>
                    <td>{{ $document->name }}</td>
                    <td>
                        @if ($document->getFirstMediaUrl())
                            <span style="color: green;">Available</span>
                        @else
                            <span style="color: red;">Not Available</span>
                        @endif
                    </td>
                    <td>
                        @if ($document->getFirstMedia())
                            {{ $document->getFirstMedia()->created_at->format('F j, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" style="text-align: center;">No Documents Found</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="summary">
    <h3>Summary</h3>
    <p><strong>Total Documents:</strong> {{ $farmer->documents->count() }}</p>
    <p><strong>Available Documents:</strong> {{ $farmer->documents->filter(function($doc) { return $doc->getFirstMediaUrl() != ''; })->count() }}</p>
    <p><strong>Missing Documents:</strong> {{ $farmer->documents->filter(function($doc) { return $doc->getFirstMediaUrl() == ''; })->count() }}</p>
</div>
@endsection
