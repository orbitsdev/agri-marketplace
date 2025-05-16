<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class PublicProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $category = ''; // Current category filter

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all(); // Fetch all categories

        $products = Product::published()
            ->approveFarmer()
            ->withRelations()
            ->available()
            ->when($this->search, function ($query) {
                $query->where('product_name', 'like', '%' . $this->search . '%')
                      ->orWhere('short_description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->paginate(10);

        return view('livewire.public-products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
