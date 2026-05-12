@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex">
                    <h2>Products</h2>

                    <a href="{{ route('products.create') }}" class="btn btn-primary ms-auto"><i class="fa fa-plus"></i> Create
                        Product</a>
                </div>
                <div class="table-responsive">
                    <table class="table card-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->count())
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->price }}$</td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>

                                            <a href="javascript:void(0)"
                                                onclick="if (confirm('Are You Sure')) { document.getElementById('delete-{{ $product->id }}').submit() } else {return false;}"
                                                class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>

                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                id="delete-{{ $product->id }}" style="display: none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">No Data Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
