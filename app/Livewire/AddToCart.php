<?php

namespace App\Livewire;

use Livewire\Component;

class AddToCart extends Component
{
    public int $quantity = 1; // Default quantity to add to cart
    public function addToCart()
    {
        dd($this->quantity); // Debugging line to check quantity
    }
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}