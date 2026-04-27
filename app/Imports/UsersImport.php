<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class UsersImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure,
    WithBatchInserts,
    ShouldQueue
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name'              => $row['name'],
                    'password'          => Hash::make($row['password'] ?? 'PageTurner@2024'),
                    'role'              => in_array($row['role'] ?? '', ['admin', 'customer'])
                                            ? $row['role']
                                            : 'customer',
                    'email_verified_at' => now(), // auto-verify bulk imports
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'max:255'],
            'email' => ['required', 'email'],
            'role'  => ['nullable', 'in:admin,customer'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'email.email' => 'The email address is not valid.',
            'role.in'     => 'Role must be either admin or customer.',
        ];
    }

    public function chunkSize(): int { return 1000; }
    public function batchSize(): int { return 1000; }
}
