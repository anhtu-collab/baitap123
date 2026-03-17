<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // Lấy sản phẩm mới nhất, phân trang 12 sản phẩm/trang
        $products = Product::orderBy('created_at', 'DESC')->paginate(12);
        return view('shop', compact('products'));
    }
    public function product_details($product_slug) 
    {
    $product = Product::where('slug', $product_slug)->first();
    // Lấy thêm các sản phẩm liên quan (bỏ qua sản phẩm hiện tại)
    $rproducts = Product::where('slug', '!=', $product_slug)->inRandomOrder()->take(8)->get();
    return view('details', compact('product', 'rproducts'));
    }
}
