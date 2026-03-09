<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //relationships

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }


    // Hark a helper method to check if user is admin!
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


        public function hasbought(Book $book)
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', function (Builder $query) use ($book) {
                $query->where('book_id', $book->id);
            })
            ->exists();
    }
}
