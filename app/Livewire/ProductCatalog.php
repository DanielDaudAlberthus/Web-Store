<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Data\ProductCollectionData;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;


class  ProductCatalog extends Component
{
    use WithPagination;
    public $queryString = [
        'select_collections' => ['except' => []],
        'search' => ['except' => ''],
        'sort_by' => ['except' => 'newest'],
];
    public array $select_collections = [];
    public string $search = '';
    public string $sort_by = 'newest'; // Default sorting value

    // handle errors in url
    public function mount(){
        $this->validate();
    }
        // Initialize the select_collections with an empty array

    // handle errors
    public function rules(){
        return [
            'select_collections' => 'array',
            'select_collections.*' => 'integer|exists:tags,id',
            'search' => 'nullable|string|min:3|max:50',
            'sort_by' => 'in:newest,latest,price_asc,price_desc',
        ];
    }
    public function applyFilters()
    {
        $this->validate();
        $this->resetPage(); // Reset pagination when filters are applied
    }
    public function resetFilters()
    {
        $this->select_collections = [];
        $this->search = '';
        $this->sort_by = 'newest'; // Reset to default sorting
        $this->resetErrorBag(); // Clear any validation errors
        $this->resetPage(); // Reset pagination when filters are reset
    }
    public function render()
    {
        // early return if there are no collections selected
        if ($this->getErrorBag()->isNotEmpty()) {
            return view('livewire.product-catalog', [
                'products' => ProductData::collect([]),
                'collections' => ProductCollectionData::collect([]),
            ]);
        }
        // Mengambil data koleksi produk dengan jumlah produk yang terkait
        $collection_result = Tag::query()
            ->wheretype('collection')
            ->withCount('products')
            ->get();
        $query = Product::query(); // Query untuk mengambil data produk

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Sorting logic
        if (!empty($this->select_collections)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('id', $this->select_collections);
            });
        }

        // Apply sorting based on the selected sort option
        switch ($this->sort_by) {
            case 'newest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest(); // Default sorting
                break;
        }

        // $result = Product::paginate((1)); // ORM / Eloquent untuk mengambil data produk
        $products = ProductData::collect(
            $query->paginate(9) // Menggunakan paginate untuk mengambil 12 produk per halaman
        );

        $collections = ProductCollectionData::collect($collection_result);
        return view('livewire.product-catalog', compact('products', 'collections'));
    }
}
