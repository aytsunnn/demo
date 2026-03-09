@extends('layouts.app')

@section('title')Создание заказа@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="col-md-8 mb-3">
                    <a href="{{ route('orders') }}" class="btn btn-secondary">
                        Назад к списку заказов
                    </a>
                </div>

                <form method="post" action="{{ route('orders.store') }}" id="orderForm">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Информация о заказе</h5>

                            <div class="mb-3">
                                <label for="date_from" class="form-label">Дата заказа</label>
                                <input type="date" class="form-control @error('date_from') is-invalid @enderror"
                                       id="date_from" name="date_from" value="{{ old('date_from', date('Y-m-d')) }}" required>
                                @error('date_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date_to" class="form-label">Дата доставки</label>
                                <input type="date" class="form-control @error('date_to') is-invalid @enderror"
                                       id="date_to" name="date_to" value="{{ old('date_to', date('Y-m-d', strtotime('+3 days'))) }}" required>
                                @error('date_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="pickupPoints_id" class="form-label">Пункт выдачи</label>
                                <select class="form-select @error('pickupPoints_id') is-invalid @enderror"
                                        id="pickupPoints_id" name="pickupPoints_id" required>
                                    <option value="">Выберите пункт выдачи</option>
                                    @foreach($pickupPoints as $point)
                                        <option value="{{ $point->id }}" {{ old('pickupPoints_id') == $point->id ? 'selected' : '' }}>
                                            {{ $point->address }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pickupPoints_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status_id" class="form-label">Статус</label>
                                <select class="form-select @error('status_id') is-invalid @enderror"
                                        id="status_id" name="status_id" required>
                                    <option value="">Выберите статус</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Товары в заказе</h5>

                            <div id="products-container">
                                <div class="product-item mb-3" id="product-0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select class="form-select product-select" name="products[0][id]" required>
                                                <option value="">Выберите товар</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}"
                                                            data-discount="{{ $product->discount }}">
                                                        {{ $product->name }} ({{ $product->price }} руб.)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control product-quantity"
                                                   name="products[0][quantity]" min="1" value="1" required
                                                   placeholder="Кол-во">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control product-price" readonly
                                                   placeholder="Цена" value="0">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger remove-product" style="display: none;">×</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-product">+ Добавить товар</button>
                        </div>
                    </div>



                    <button type="submit" class="btn">Создать заказ</button>

                </form>
            </div>
        </div>
    </div>

    <script>
        let productIndex = 1;

        function updatePrice(row) {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.product-quantity');
            const priceInput = row.querySelector('.product-price');

            if (select.value && quantity.value) {
                const option = select.options[select.selectedIndex];
                const price = parseFloat(option.dataset.price) || 0;
                const discount = parseFloat(option.dataset.discount) || 0;
                const discountedPrice = price - (price * discount / 100);

                priceInput.value = (discountedPrice * parseInt(quantity.value)).toFixed(2);
            } else {
                priceInput.value = '0';
            }

            updateTotalAmount();
        }

        function updateTotalAmount() {
            let total = 0;
            document.querySelectorAll('.product-price').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        document.getElementById('add-product').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const newRow = document.querySelector('.product-item').cloneNode(true);

            newRow.id = 'product-' + productIndex;
            newRow.querySelectorAll('input, select').forEach(field => {
                field.name = field.name.replace('[0]', '[' + productIndex + ']');
                if (field.classList.contains('product-quantity')) {
                    field.value = 1;
                } else if (field.classList.contains('product-select')) {
                    field.value = '';
                } else if (field.classList.contains('product-price')) {
                    field.value = '0';
                }
            });

            newRow.querySelector('.remove-product').style.display = 'inline-block';
            container.appendChild(newRow);
            productIndex++;
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select') ||
                e.target.classList.contains('product-quantity')) {
                const row = e.target.closest('.product-item');
                updatePrice(row);
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                e.target.closest('.product-item').remove();
                updateTotalAmount();
            }
        });
    </script>
@endsection
