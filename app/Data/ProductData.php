<?php
declare(strict_types=1); //agar semua tipe data harus didefinisikan

namespace App\Data;

use App\Models\Product;
use BcMath\Number;
use Illuminate\Support\Number as SupportNumber;
use Livewire\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    #[Computed]
    public string $price_formatted;
    public function __construct(
        public string $name,
        public string $short_desc,
        public string $slug,
        public string $sku,
        public string|Optional|null $description,
        public float $price,
        public int $stock,
        public int $weight,
        public string $cover_url,
    ) {
        $this->price_formatted = SupportNumber::currency($price);
    }

    public static function fromModel(Product $product): self
    {
        return new self(
            $product->name,
            $product->tags()->where('type', 'collection')->pluck('name')->implode(', '),
            $product->slug,
            $product->sku,
            $product->description,
            floatval($product->price),
            $product->stock,
            $product->weight,
            $product->getFirstMediaUrl('cover'), // Menggunakan metode getFirstMediaUrl untuk mendapatkan URL media pertama dari koleksi 'cover'
        );
    }

}