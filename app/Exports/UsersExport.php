<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected bool $redactPII;

    public function __construct(bool $redactPII = false)
    {
        $this->redactPII = $redactPII;
    }

    public function query()
    {
        return User::query()->select([
            'id', 'name', 'email', 'role',
            'email_verified_at', 'created_at'
            // ✅ Never select: password, two_factor_code,
            //    two_factor_expires_at, remember_token
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Role', 'Verified', 'Joined'];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $this->redactPII ? $this->redact($user->name) : $user->name,
            $this->redactPII ? $this->redact($user->email) : $user->email,
            $user->role,
            $user->email_verified_at ? 'Yes' : 'No',
            $user->created_at->format('Y-m-d'),
        ];
    }

    // GDPR redaction: keep first char, mask the rest
    private function redact(string $value): string
    {
        if (str_contains($value, '@')) {
            // Email: show domain, mask local part
            [$local, $domain] = explode('@', $value);
            return substr($local, 0, 1) . str_repeat('*', strlen($local) - 1) . '@' . $domain;
        }

        return substr($value, 0, 1) . str_repeat('*', max(strlen($value) - 1, 3));
    }
}
