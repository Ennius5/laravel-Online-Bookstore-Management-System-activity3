<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    // Cached category IDs — loaded once, reused for all records
    private static ?array $categoryIds = null;

    // Publisher pool
    private static array $publishers = [
        'Penguin Random House', 'HarperCollins', 'Simon & Schuster',
        'Hachette Book Group', 'Macmillan Publishers', 'Scholastic',
        'Oxford University Press', 'Cambridge University Press',
        'Wiley', 'Springer', 'Elsevier', 'MIT Press',
        'Harvard University Press', 'Pearson', 'McGraw-Hill',
    ];

    public function definition(): array
    {
        // Load category IDs once
        if (self::$categoryIds === null) {
            self::$categoryIds = Category::pluck('id')->toArray();

            // Fallback if no categories exist
            if (empty(self::$categoryIds)) {
                self::$categoryIds = [1];
            }
        }

        // Format-based pricing
        $format = $this->faker->randomElement([
            'hardcover', 'paperback', 'ebook', 'audiobook'
        ]);

        $basePrice = match($format) {
            'hardcover'  => $this->faker->randomFloat(2, 20.00, 80.00),
            'paperback'  => $this->faker->randomFloat(2, 8.00,  25.00),
            'ebook'      => $this->faker->randomFloat(2, 2.99,  14.99),
            'audiobook'  => $this->faker->randomFloat(2, 12.00, 35.00),
        };

        return [
            'isbn'           => $this->generateValidIsbn13(),
            'title'          => $this->faker->sentence(rand(2, 6)),
            'author'         => $this->faker->name(),
            'publisher'      => $this->faker->randomElement(self::$publishers),
            'price'          => $basePrice,
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'category_id'    => $this->faker->randomElement(self::$categoryIds),
            'format'         => $format,
            'is_active'      => $this->faker->boolean(85), // 85% active
            'published_at'   => $this->faker->dateTimeBetween('-30 years', 'now'),
            'description'    => $this->faker->paragraphs(2, true),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }


    // Bestseller state — high stock, always active
    public function bestseller(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock_quantity' => $this->faker->numberBetween(500, 1000),
            'is_active'      => true,
            'price'          => $this->faker->randomFloat(2, 15.00, 40.00),
        ]);
    }

    // Valid ISBN-13 generator with proper checksum
    private function generateValidIsbn13(): string
    {
        $prefix = '978';
        // Use microtime for uniqueness instead of pure random
        $digits = $prefix . substr(str_replace('.', '', microtime(true)), -9);

        // Pad or trim to exactly 12 digits
        $digits = substr(str_pad($digits, 12, (string)rand(0,9)), 0, 12);

        // Calculate check digit
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $digits[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $digits . $checkDigit;
    }
}
