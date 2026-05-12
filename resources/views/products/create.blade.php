@extends('layouts.app')

@section('content')

    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-12">
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <div class="card">
                    <div class="card-header d-flex">
                        <h3>Create Product</h3>
                        <a href="{{ route('products.index') }}" class="btn btn-primary ms-auto"><i class="fa fa-home"></i> Home</a>
                    </div>

                    <div class="card-body">
                        <form action="{{route('products.store')}}" method="post">
                            @csrf

                            <div class="row m-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product_name">
                                            Product Name
                                        </label>

                                        <input class="form-control" type="text" name="name" id="" value="{{ old('name') }}">

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

                                        <textarea name="description" class="form-control" name="description">{{ old('description') }}</textarea>

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

                                        <input class="form-control" type="text" name="price" id="" value="{{ old('price') }}">

                                        @error('price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pt-3">
                                <button type="submit" name="save" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection