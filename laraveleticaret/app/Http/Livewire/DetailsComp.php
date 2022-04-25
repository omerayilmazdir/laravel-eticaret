<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;
use App\Models\Product;
use Cart;
class DetailsComp extends Component
{
    public $slug;
    public $qty;
    public function mount($slug){
        $this->slug = $slug;
        $this->qty = 1;
    }
    public  function store($product_id,$product_name,$product_price){
        Cart::instance('cart')->add($product_id,$product_name,$this->qty,$product_price)->associate('App\Models\Product');
        session()->flash('success_message','Ürünler sepete eklendi.');
        return redirect()->route('product.cart');
    }

    public function increaseQuantity(){
        $this->qty++;
    }

    public function decreaseQuantity(){
        if($this->qty > 1){
            $this->qty--;
        }
    }


    public function render()
    {
        $product = Product::where('slug', $this->slug)->first();
        $popular_products = Product::inRandomOrder()->limit(4)->get();
        $relatad_products = Product::where('category_id',$product->category_id)->inRandomOrder()->limit(5)->get();
        $sale = Sale::find(1);
        return view('livewire.details-comp',['product' => $product,'popular_products'=>$popular_products,'relatad_products'=>$relatad_products,'sale'=>$sale])->layout('layouts.master');
    }
}
