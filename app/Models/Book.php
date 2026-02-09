<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'isbn',
        'price',
        'stock_quantity',
        'description',
        'cover_image'
    ];

     protected $appends = ['cover_image_url'];

    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            // Return a default image or null
            return asset('images/default-book-cover.jpg');
        }

        // Check if it's already a URL (full path)
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        // Check if it's stored in storage
        if (Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        // Try direct asset path as fallback
        return asset($this->cover_image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper method to check stock availability
    public function hasStock($quantity = 1)
    {
        return $this->stock_quantity >= $quantity;
    }


        public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Or if you want to store it in the database (more efficient)
    public function updateAverageRating()
    {
        $this->average_rating = $this->reviews()->avg('rating');
        $this->save();
    }


}
