<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Data\ProductData;
use App\Models\Product;
use Livewire\Component;

class  ProductCatalog extends Component
{
    public function render()
    {
        $query = Product::paginate((1)); // ORM / Eloquent untuk mengambil data produk
        $products = ProductData::collect($query);
        return view('livewire.product-catalog', compact('products'));
    }
}
