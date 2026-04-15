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
                @if(session('warning'))
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" style="width: 20px; height: 20px" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div>
                                {{session('warning')}}
                            </div>
                        </div>
                    @endif

                @if($role_id === 1 || $role_id === 2)
                    <div class="card mb-3">
                        <div class="card-body">
                            @if($role_id === 1)
                                <a href="{{route('products.create')}}" class="btn btn-primary">Добавить товар</a>
                                <button id="editButton" class="btn btn-primary">Редактировать товар</button>
                                <button id="deleteButton" class="btn btn-primary">Удалить товар</button>
                            @endif
                            <a href="{{route('orders')}}" class="btn btn-primary">Заказы</a>
                                <div class="mb-3 mt-2">
                                    <label for="search" class="form-label">Поиск</label>
                                    <input type="text" class="form-control" id="search">
                                </div>
                                <div class="mb-3">
                                    <label for="filter" class="form-label">Фильтрация</label>
                                    <select type="text" class="form-select" id="filter">
                                        <option value="">Все поставщики</option>
                                        @foreach($sellers as $seller)
                                            <option value="{{$seller->id}}">{{$seller->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="">
                                    <label for="sort" class="form-label">Сортировка</label>
                                    <select type="text" class="form-select" id="sort">
                                        <option value="">Без сортировки</option>
                                        <option value="asc">По возрастанию</option>
                                        <option value="desc">По убыванию</option>
                                    </select>
                                </div>
                        </div>
                    </div>

                @endif

                <div class="card">
                    <div class="card-body" id="productsList">
                        @foreach($products as $product)
                        <div class="card mb-3 product-card"
                             data-seller = "{{$product->seller->id}}"
                             data-quantity = "{{$product->quantity}}"
                             onclick="selectProduct(this, {{$product->id}})">
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

    <form id="deleteForm" method="post">
        @csrf
        @method('delete')
    </form>

    <style>
        .product-card{
            border: 1px solid black;
        }
        .product-card.selected{
            border: 1px solid red;
        }
    </style>

    <script>
        let selectedProductId = null;

        function selectProduct(card, id){
            document.querySelectorAll('.product-card').forEach(c =>
            c.classList.remove('selected'));

            if(selectedProductId === id){
                selectedProductId = null;
                return;
            }

            card.classList.add('selected');
            selectedProductId = id;
        }

        document.getElementById('editButton').onclick = () =>{
            if(selectedProductId){
                location.href=`/products/${selectedProductId}/edit`;
            }
        }

        document.getElementById('deleteButton').onclick = () =>{
            if(selectedProductId && confirm('Удалить товар?')){
                const form = document.getElementById('deleteForm');
                form.action = `/products/${selectedProductId}`;
                form.submit();
            }
        }

        const search   = document.getElementById('search');
        const seller = document.getElementById('filter');
        const sort     = document.getElementById('sort');
        const cards    = [...document.querySelectorAll('.product-card')];
        const list     = document.getElementById('productsList');

        [search, seller, sort].forEach(el =>
            el.addEventListener('input', applyFilters)
        );

        // Функция применения фильтров
        function applyFilters() {
            const text = search.value.toLowerCase();
            const sel  = seller.value;
            const ord  = sort.value;

            let result = cards.filter(card => {
                // Поиск по всему тексту в карточке
                const cardText = card.innerText.toLowerCase();
                const matchesSearch = !text || cardText.includes(text);

                // Фильтр по поставщику
                const matchesSupplier = (!sel || card.dataset.seller === sel);

                return matchesSearch && matchesSupplier;
            });

            if (ord) {
                result.sort((a, b) =>
                    ord === 'asc'
                        ? a.dataset.quantity - b.dataset.quantity
                        : b.dataset.quantity - a.dataset.quantity
                );
            }

            list.innerHTML = '';
            result.forEach(card => list.appendChild(card));
        }
    </script>
@endsection
