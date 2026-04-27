@extends('layouts.app')

@section('title', 'Backup Management')

@section('header')
    <h2 class="text-xl font-semibold">Backup Management</h2>
@endsection

@section('content')
@if(session('status'))
    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-4 rounded">
        {{ session('status') }}
    </div>
@endif
<div class="p-6 bg-white rounded shadow">
    <form method="POST" action="{{ route('admin.backup.trigger') }}">
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
            Run Backup Now
        </button>
    </form>

    <h3 class="mt-6 text-lg font-medium">Backup Status</h3>
    <table class="w-full mt-2 border">
        <thead>
            <tr>
                <th class="p-2 border">Disk</th>
                <th class="p-2 border">Healthy</th>
                <th class="p-2 border"># Backups</th>
                <th class="p-2 border">Newest Backup</th>
                <th class="p-2 border">Storage Used</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($backupStatus as $status)
                <tr>
                    <td class="p-2 border">{{ $status['disk'] }}</td>
                    <td class="p-2 border">
                        <span class="{{ $status['healthy'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $status['healthy'] ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="p-2 border">{{ $status['amount'] }}</td>
                    <td class="p-2 border">{{ $status['newest'] ?? 'None' }}</td>
                    <td class="p-2 border">{{ $status['used_storage'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        No backups found. Run a backup first using the button above.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
