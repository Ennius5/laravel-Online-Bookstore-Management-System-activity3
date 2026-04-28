@extends('layouts.app')

@section('title', 'Audit Detail')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Audit Detail</h2>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div><strong>Event:</strong> {{ $audit->event }}</div>
        <div><strong>User Snapshot:</strong>
            @php
                $userRaw = $audit->getRawOriginal('user');
                $userData = $userRaw ? (is_string($userRaw) ? json_decode($userRaw) : $userRaw) : null;
            @endphp
            @if($userData)
                {{ $userData->name }} ({{ $userData->email }})
            @else
                System
            @endif
        </div>
        <div><strong>Model:</strong> {{ class_basename($audit->auditable_type) }}</div>
        <div><strong>Record ID:</strong> {{ $audit->auditable_id }}</div>
        <div><strong>IP Address:</strong> {{ $audit->ip_address }}</div>
        <div><strong>URL:</strong> {{ $audit->url }}</div>
        <div><strong>User Agent:</strong> {{ $audit->user_agent }}</div>
        <div><strong>Date:</strong> {{ $audit->created_at }}</div>
    </div>

    <h3 class="text-lg font-medium mt-4 mb-2">Changes</h3>
    <table class="w-full border">
        <thead>
            <tr>
                <th class="p-2 border">Field</th>
                <th class="p-2 border">Old Value</th>
                <th class="p-2 border">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audit->getModified() as $field => $change)
                <tr>
                    <td class="p-2 border">{{ $field }}</td>
                    <td class="p-2 border">{{ $change['old'] ?? 'NULL' }}</td>
                    <td class="p-2 border">{{ $change['new'] ?? 'NULL' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.audit.index') }}" class="mt-4 inline-block text-blue-600">← Back to Audit Log</a>
</div>
@endsection
