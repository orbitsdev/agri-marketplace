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
            <th>Created At</th>
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
                <td>{{ $farmer->status ?? 'N/A' }}</td>
                <td>{{ $farmer->user->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ $farmer->created_at ? $farmer->created_at->format('F j, Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
