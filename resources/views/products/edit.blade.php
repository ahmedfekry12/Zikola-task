@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="card">
                    <div class="card-header d-flex">
                        <h3>Edit Product</h3>
                        <a href="{{ route('products.index') }}" class="btn btn-primary ms-auto"><i class="fa fa-home"></i>
                            Home</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row m-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product_name">
                                            Product Name
                                        </label>

                                        <input class="form-control" type="text" name="name" id=""
                                            value="{{ old('name', $product->name) }}">

                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-3 col-12">
                                    <div class="form-group">
                                        <label for="product_description">
                                            Product Description
                                        </label>

                                        <textarea name="description" class="form-control" name="description">{{ old('description', $product->description) }}</textarea>

                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-3 col-12">
                                    <div class="form-group">
                                        <label for="product_price">
                                            Product Price
                                        </label>

                                        <input class="form-control" type="text" name="price" id=""
                                            value="{{ old('price', $product->price) }}">

                                        @error('price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
