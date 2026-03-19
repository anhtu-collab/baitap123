@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center mb-24 justify-between gap20 flex-wrap">
            <h3>Edit Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{route('admin.index')}}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{route('admin.products')}}"><div class="text-tiny">Products</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit product</div></li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}">
            @csrf
            @method('PUT') <input type="hidden" name="id" value="{{$product->id}}">

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" value="{{$product->name}}" required="">
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" value="{{$product->slug}}" required="">
                </fieldset>

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id">
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id">
                                @foreach ($brands as $brand)
                                <option value="{{$brand->id}}" {{$product->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="short_description" required="">{{$product->short_description}}</textarea>
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" required="">{{$product->description}}</textarea>
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Upload images <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        @if($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{asset('uploads/products')}}/{{$product->image}}" class="effect8" alt="">
                        </div>
                        @endif
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="flex-grow">
                            <div class="upload-image">
                                <div id="galpreview" class="flex-grow flex-wrap gap10" style="display:flex">
                                    @if($product->images)
                                        @foreach(explode(',', $product->images) as $img)
                                            <div class="item gitems">
                                                <img src="{{asset('uploads/products')}}/{{trim($img)}}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div id="g-upload-file" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="regular_price" value="{{$product->regular_price}}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input type="text" name="sale_price" value="{{$product->sale_price}}">
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span></div>
                        <input type="text" name="SKU" value="{{$product->SKU}}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input type="text" name="quantity" value="{{$product->quantity}}">
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select">
                            <select name="stock_status">
                                <option value="instock" {{$product->stock_status == 'instock' ? 'selected' : ''}}>InStock</option>
                                <option value="outofstock" {{$product->stock_status == 'outofstock' ? 'selected' : ''}}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select">
                            <select name="featured">
                                <option value="0" {{$product->featured == 0 ? 'selected' : ''}}>No</option>
                                <option value="1" {{$product->featured == 1 ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Update product</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // Xem trước ảnh chính khi chọn file mới
        $("#myFile").on("change", function(e){
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        // Xem trước gallery ảnh mới (xóa các preview cũ khi chọn file mới)
        $("#gFile").on("change", function(e){
            const gphotos = this.files;
            $("#galpreview").html(""); // Xóa preview cũ
            $.each(gphotos, function(key, val){
                $("#galpreview").append(`<div class="item gitems"><img src="${URL.createObjectURL(val)}"></div>`);
            });
        });

        $("input[name='name']").on("change", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(Text) {
        return Text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
</script>
@endpush