<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
     
    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }
    public function add_brand()
    {
        return view('admin.brand-add');
    }
    public function brand_store(Request $request){
        $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:brands,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
         $brand = new Brand();
         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         $image = $request->file('image');
         $file_extention = $request->file('image')->extension();
         $file_name = Carbon::now()->timestamp.'.'.$file_extention;
         $this->GenerateBrandThumbailsImage($image,$file_name);
         $brand ->image = $file_name;
         $brand ->save();
         return Redirect() ->route('admin.brands')->with('status','Brand has been added succesfully!');

    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }
    public function brand_update(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:brands,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = Brand::find($request->id);
         $brand->name = $request->name;
         $brand->slug = Str::slug($request->name);
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image))
                {
                    File::delete(public_path('uploads/brands').'/'.$brand->image);
                }
                 $image = $request->file('image');
                 $file_extention = $request->file('image')->extension();
                 $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GenerateBrandThumbailsImage($image,$file_name);
         $brand ->image = $file_name;
         }
        
         $brand ->save();
         return Redirect() ->route('admin.brands')->with('status','Brand has been updated succesfully!');
    }
    public function GenerateBrandThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();

        })->save($destinationPath.'/'.$imageName);
    }
    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $brand->delete();
            return redirect()->route('admin.brands')->with('status','Brand has been deleted succesfully!');
    }
    public function categories()
    {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }
    public function category_add()
    {
        return view('admin.category-add');
    }
    public function category_store(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:categories,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
         $category = new Category();
         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         $image = $request->file('image');
         $file_extention = $request->file('image')->extension();
         $file_name = Carbon::now()->timestamp.'.'.$file_extention;
         $this->GenerateCategoryThumbailsImage($image,$file_name);
         $category ->image = $file_name;
         $category ->save();
         return Redirect() ->route('admin.categories')->with('status','category has been added succesfully!');
    }
      public function GenerateCategoryThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();

        })->save($destinationPath.'/'.$imageName);
    }
    
    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }
    public function category_update(Request $request)
    {
          $request ->validate([
            'name' =>'required',
            'slug'=>'required|unique:categories,slug',
            'image' =>'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = Category::find($request->id);
         $category->name = $request->name;
         $category->slug = Str::slug($request->name);
         if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image))
                {
                    File::delete(public_path('uploads/categories').'/'.$category->image);
                }
                 $image = $request->file('image');
                 $file_extention = $request->file('image')->extension();
                 $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GenerateCategoryThumbailsImage($image,$file_name);
         $category ->image = $file_name;
         }
        
         $category ->save();
         return Redirect() ->route('admin.categories')->with('status','category has been updated succesfully!');
    }
    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
            {
                File::delete(public_path('uploads/categories').'/'.$category->image);

            }
            $category->delete();
            return redirect()->route('admin.categories')->with('status','category has been deleted succesfully!');

    }
    public function products()
    {
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        // Xử lý ảnh chính (Main Image)
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        // Xử lý Gallery ảnh
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $check = in_array($gextension, $allowedfileExtension);
                if($check) {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailsImage($file, $gfilename);
                    array_push($gallery_arr, $gfilename);
                    $counter++;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
    }

    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        
        // Tạo thư mục nếu chưa có
        if (!File::exists($destinationPathThumbnail)) {
            File::makeDirectory($destinationPathThumbnail, 0755, true);
        }

        $img = Image::read($image->path());

        // Ảnh gốc được resize theo kích thước chuẩn e-commerce (540x689)
        $img->cover(540, 689, "top");
        $img->save($destinationPath . '/' . $imageName);

        // Ảnh Thumbnail nhỏ (104x104)
        $img->resize(104, 104, function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
    }
    // Thêm vào AdminController.php

public function product_edit($id)
{
    $product = Product::find($id);
    $categories = Category::select('id', 'name')->orderBy('name')->get();
    $brands = Brand::select('id', 'name')->orderBy('name')->get();
    return view('admin.product-edit', compact('product', 'categories', 'brands'));
}

public function product_update(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id . ',id',
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'mimes:png,jpg,jpeg|max:2048',
        'category_id' => 'required',
        'brand_id' => 'required',
    ]);

    $product = Product::find($request->id);
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    // Xử lý ảnh chính khi có thay đổi
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại
        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        $image = $request->file('image');
        $imageName = $current_timestamp . '.' . $image->extension();
        $this->GenerateProductThumbnailsImage($image, $imageName);
        $product->image = $imageName;
    }

    // Xử lý Gallery ảnh khi có thay đổi
    if ($request->hasFile('images')) {
        // Xóa tất cả ảnh gallery cũ
        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }

        $gallery_arr = array();
        $counter = 1;
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
            $this->GenerateProductThumbnailsImage($file, $gfilename);
            array_push($gallery_arr, $gfilename);
            $counter++;
        }
        $product->images = implode(',', $gallery_arr);
    }

    $product->save();
    return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
}
// Thêm vào AdminController.php

public function product_delete($id)
{
    $product = Product::find($id);

    // 1. Xóa ảnh chính và ảnh thumbnail chính
    if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
        File::delete(public_path('uploads/products') . '/' . $product->image);
    }
    if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
        File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
    }

    // 2. Xóa các ảnh trong gallery (bao gồm cả thumbnail của gallery)
    if (!empty($product->images)) {
        foreach (explode(',', $product->images) as $gfile) {
            $gfile = trim($gfile);
            if (File::exists(public_path('uploads/products') . '/' . $gfile)) {
                File::delete(public_path('uploads/products') . '/' . $gfile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $gfile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $gfile);
            }
        }
    }

    // 3. Xóa bản ghi trong Database
    $product->delete();
    
    return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
}

}
