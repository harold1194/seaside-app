<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;

class ShopComponent extends Component
{
    public $sorting;
    public $pagesize;

    public $min_price;
    public $max_price;

    //this function mount hook method for bookmaker
    public function mount()
    {
      $this->sorting = "default";
      $this->pagesize = 12;

      $this->min_price = 1;
      $this->max_price = 1000;
    }
    //this function store product
    public function store($product_id,$product_name,$product_price)
    {
        Cart::instance('cart')->add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message','Item added in cart');
        return redirect()->route('product.cart');
    }

    // this function for adding wishlist functional
    public function addToWishList($product_id,$product_name,$product_price)
    {
      Cart::instance('wishlist')->add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
      $this->emitTo('wishlist-count-component','refreshComponent');
    }

    // This function for removal of wishlist
    public function removeFromWishList($product_id)
    {
      foreach (Cart::instance('wishlist')->content() as $witem) {
        if ($witem->id == $product_id) {
          Cart::instance('wishlist')->remove($witem->rowId);
          $this->emitTo('wishlist-count-component','refreshComponent');
          return;
        }
      }
    }
    
    use WithPagination;
    public function render()
    {
        if ($this->sorting=='date') {
          $products = Product::whereBetween('regular_price', [$this->min_price,$this->max_price])->orderBy('created_at','DESC')->paginate($this->pagesize);
        }
        elseif ($this->sorting=='price') {
          $products = Product::whereBetween('regular_price', [$this->min_price,$this->max_price])->orderBy('regular_price','ASC')->paginate($this->pagesize);
        }
        elseif ($this->sorting=='price') {
          $products = Product::whereBetween('regular_price', [$this->min_price,$this->max_price])->orderBy('regular_price','DESC')->paginate($this->pagesize);
        }
        else {
          $products = Product::whereBetween('regular_price', [$this->min_price,$this->max_price])->paginate($this->pagesize);
        }

        $categories = Category::all();
        return view('livewire.shop-component',['products'=> $products, 'categories' => $categories])->layout('layouts.base');
    }
}
