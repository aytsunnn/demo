@extends('layouts.app')

@section('title')Список товаров@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" style="width: 20px;height: 20px" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                        <div>
                            {{session('success')}}
                        </div>
                    </div>
                @endif

                @if($role_id === 1 || $role_id === 2)
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{route('products.create')}}" class="btn btn-primary">Добавить товар</a>
                            <button id="editButton" class="btn btn-primary">Редактировать товар</button>
                            <button id="deleteButton" class="btn btn-primary">Удалить товар</button>
                            <a href="/" class="btn btn-primary">Заказы</a>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @foreach($products as $product)
                        <div class="card mb-3 product-card" onclick="selectProduct(this, {{$product->id}})">
                            <div class="d-flex card-body">
                                <div class="border border-dark border-2">
                                    <img src="{{$product->image_path ? asset('assets/images/'.$product->image_path) : asset('assets/images/picture.png')}}" style="width: 200px; height: 200px; object-fit: contain">
                                </div>
                                <div class="col-md-7 border border-dark border-1" style="margin-left: 30px">
                                    <div class="card-body row">
                                        <span><strong>{{$product->category->name}} | {{$product->name}}</strong></span>
                                        <span>Описание: {{$product->description}}</span>
                                        <span>Производитель: {{$product->manufacturer->name}}</span>
                                        <span>Поставщик: {{$product->seller->name}}</span>
                                        <span>Цена:
                                            @if($product->discount == 0)
                                                {{$product->price}} руб.
                                            @else
                                                <del style="color: red">{{$product->price}} руб.</del> {{$product->price - $product->price / 100 * $product->discount}} руб.
                                            @endif
                                        </span>
                                        <span style="{{$product->quantity == 0 ? 'color:#aaa' : ''}}">Количество на складе: {{$product->quantity}} шт.</span>
                                    </div>
                                </div>
                                <div class="border border-dark border-2 d-flex justify-content-center align-items-center" style="width: 100px; color: white; {{$product->discount >= 15? 'background-color:#2E8B57' : 'background-color:red'}}">
                                    <span>{{$product->discount}}%</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
