@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Audit Log</h2>

    <!-- Filters -->
    <form method="GET" class="mb-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        <input type="text" name="user_id" placeholder="User ID" value="{{ request('user_id') }}" class="border p-2">
        <select name="event" class="border p-2">
            <option value="">All Events</option>
            @foreach($events as $event)
                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>{{ $event }}</option>
            @endforeach
        </select>
        <select name="auditable_type" class="border p-2">
            <option value="">All Models</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border p-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Table -->
    <table class="w-full border">
        <thead>
            <tr>
                <th class="p-2 border">Date</th>
                <th class="p-2 border">User</th>
                <th class="p-2 border">Event</th>
                <th class="p-2 border">Model</th>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($audits as $audit)
                <tr>
                    <td class="p-2 border">{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                    <td class="p-2 border">
                        @php
                            $userName = 'System';
                            $userRaw = $audit->getRawOriginal('user');   // reads the JSON column directly
                            if ($userRaw) {
                                $userData = is_string($userRaw) ? json_decode($userRaw) : $userRaw;
                                $userName = $userData->name ?? 'System';
                            }
                        @endphp
                        {{ $userName }}
                    </td>
                    <td class="p-2 border">{{ $audit->event }}</td>
                    <td class="p-2 border">{{ class_basename($audit->auditable_type) }}</td>
                    <td class="p-2 border">{{ $audit->auditable_id }}</td>
                    <td class="p-2 border">
                        <a href="{{ route('admin.audit.show', $audit->id) }}" class="text-blue-600">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-4 text-center">No audit records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $audits->links() }}
</div>
@endsection
