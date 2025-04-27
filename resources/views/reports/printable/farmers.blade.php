@extends('reports.printable.layout')

@section('content')
<h2>Farmers Report</h2>

<table>
    <thead>
        <tr>
            <th>Farm Owner</th>
            <th>Email</th>
            <th>Farm Name</th>
            <th>Location</th>
            <th>Farm Size</th>
            <th>Status</th>
            <th>Account Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($farmers as $farmer)
            <tr>
                <td>{{ $farmer->user->fullName ?? 'N/A' }}</td>
                <td>{{ $farmer->user->email ?? 'N/A' }}</td>
                <td>{{ $farmer->farm_name ?? 'N/A' }}</td>
                <td>{{ $farmer->location ?? 'N/A' }}</td>
                <td>{{ $farmer->farm_size ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ 
                        $farmer->status === 'Pending' ? 'badge-info' : 
                        ($farmer->status === 'Approved' ? 'badge-success' : 
                        ($farmer->status === 'Rejected' || $farmer->status === 'Blocked' ? 'badge-danger' : 'badge-secondary')) 
                    }}">
                        {{ $farmer->status }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $farmer->user->is_active ? 'badge-success' : 'badge-danger' }}">
                        {{ $farmer->user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal-row">
            <td colspan="6" style="text-align: right;">Total Farmers</td>
            <td>{{ count($farmers) }}</td>
        </tr>
    </tfoot>
</table>

<div class="summary">
    <h3>Summary</h3>
    
    <h4>Farmers by Application Status:</h4>
    @php
        $statusCounts = $farmers->groupBy('status')->map->count();
        $totalFarmers = count($farmers);
    @endphp
    
    <ul>
        @foreach($statusCounts as $status => $count)
            <li>{{ $status }}: {{ $count }} ({{ round(($count / $totalFarmers) * 100, 2) }}%)</li>
        @endforeach
    </ul>
    
    <h4>Farmers by Account Status:</h4>
    @php
        $activeFarmers = $farmers->filter(function($farmer) {
            return $farmer->user->is_active;
        })->count();
        
        $inactiveFarmers = $totalFarmers - $activeFarmers;
    @endphp
    
    <ul>
        <li>Active: {{ $activeFarmers }} ({{ round(($activeFarmers / $totalFarmers) * 100, 2) }}%)</li>
        <li>Inactive: {{ $inactiveFarmers }} ({{ round(($inactiveFarmers / $totalFarmers) * 100, 2) }}%)</li>
    </ul>
</div>
@endsection
