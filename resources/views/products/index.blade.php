@extends('layouts.app')
@section('title')Список товаров@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Кнопки действий --}}
                @if($role_id === 1)
                <div class="mb-3 d-flex gap-2">
                    <a href="{{ route('products.create') }}" class="btn ">Добавить товар</a>
                    <button id="editButton" class="btn ">Редактировать выбранный</button>
                    <button id="deleteButton" class="btn ">Удалить выбранный</button>
                    <a href="{{ route('orders') }}" class="btn">Заказы</a>
                </div>
                @endif

                {{-- Поиск, фильтрация и сортировка --}}
                @if($role_id === 1 || $role_id === 2)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Поиск и фильтрация</h5>

                        <input type="text" id="search" class="form-control mb-2" placeholder="Поиск...">

                        <select id="supplier" class="form-select mb-2">
                            <option value="">Все поставщики</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ strtolower($supplier->name) }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        <select id="sort" class="form-select mb-3">
                            <option value="">Без сортировки</option>
                            <option value="asc">По увеличению</option>
                            <option value="desc">По убыванию</option>
                        </select>
                    </div>
                </div>
                @endif

                {{-- Список товаров --}}
                <div id="productsList">
                    @foreach($products as $product)
                        <div class="card product-card mb-3"
                             id="product-{{ $product->id }}"
                             data-id="{{ $product->id }}"
                             data-text="{{ strtolower(
                                $product->name.' '.
                                $product->article.' '.
                                $product->description.' '.
                                ($product->category->name ?? '').' '.
                                ($product->manufacturer->name ?? '').' '.
                                ($product->supplier->name ?? '')
                             ) }}"
                             data-supplier="{{ strtolower($product->supplier->name ?? '') }}"
                             data-quantity="{{ $product->quantity }}"


                             style="cursor: pointer"
                             onclick="selectProduct(this, {{ $product->id }})">
                            <div class="card-body d-flex">
                                <div class="border border-dark border-2">
                                    <img src="{{ $product->image_path ? asset('assets/images/' . $product->image_path) : asset('assets/images/picture.png') }}"
                                         style="height: 170px; width: 170px; object-fit: contain;" alt="picture" />
                                </div>
                                <div class="d-flex border border-dark p-1 flex-column flex-grow-1 ms-3">
                                    <span>{{ $product->category ? $product->category->name : 'Нет' }} <strong>|</strong> {{ $product->name }}</span>
{{--                                    <span>{{ $product->name }}</span>--}}
                                    <span>{{ $product->description }}</span>
                                    <span>{{ $product->manufacturer ? $product->manufacturer->name : 'Нет' }}</span>
                                    <span>{{ $product->supplier ? $product->supplier->name : 'Нет' }}</span>
                                    <span>
                                        @if($product->discount >0)
                                            <span style="color: red"><del>{{ $product->price }} руб.</del></span>
                                            {{ $product->price - ($product->price / 100 * $product->discount) }} руб.
                                        @else
                                            {{ $product->price }} руб.
                                        @endif
                                    </span>
                                    <span>
                                        @if($product->quantity === 0)
                                            <span style="color: blue">{{ $product->quantity }} шт.</span>
                                        @else
                                            {{ $product->quantity }} шт.
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex border border-2 border-dark align-items-center justify-content-center" style="width: 70px; {{ $product->discount >= 20 ? 'background-color: #2E8B57; color:white' : '' }}">
                                    <span>
                                        {{ $product->discount }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Сообщение если товаров не найдено --}}
                <div id="noProductsMessage" class="alert alert-info" style="display: none;">
                    Товары не найдены. Попробуйте изменить параметры поиска.
                </div>
            </div>
        </div>
    </div>

    {{-- Скрытая форма для удаления --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let selectedProductId = null;

        // Функция выбора карточки
        function selectProduct(card, id) {
            document.querySelectorAll('.product-card').forEach(c =>
                c.classList.remove('selected')
            );

            if (selectedProductId === id) {
                selectedProductId = null;
                return;
            }

            card.classList.add('selected');
            selectedProductId = id;
        }

        document.getElementById('editButton').onclick = () => {
            if (selectedProductId) {
                location.href = `/products/${selectedProductId}/edit`;
            }
        };

        document.getElementById('deleteButton').onclick = () => {
            if (selectedProductId && confirm('Удалить товар?')) {
                const form = document.getElementById('deleteForm');
                form.action = `/products/${selectedProductId}`;
                form.submit();
            }
        };

        const search   = document.getElementById('search');
        const supplier = document.getElementById('supplier');
        const sort     = document.getElementById('sort');
        const cards    = [...document.querySelectorAll('.product-card')];
        const list     = document.getElementById('productsList');

        [search, supplier, sort].forEach(el =>
            el.addEventListener('input', applyFilters)
        );

        // Функция применения фильтров
        function applyFilters() {
            const text = search.value.toLowerCase();
            const sup  = supplier.value;
            const ord  = sort.value;

            let result = cards.filter(card =>
                card.dataset.text.includes(text) &&
                (!sup || card.dataset.supplier === sup)
            );

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

    <style>
        .product-card {
            border: 1px solid #dee2e6;
        }
        .product-card.selected {
            border: 2px solid #D97A54 !important;
        }
    </style>
@endsection
