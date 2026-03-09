@extends('layouts.app')

@section('title')Создание товара@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <a href="{{ route('products') }}" class="btn btn-secondary">
                    Назад к списку товаров
                </a>
            </div>

            @if ($errors->any())
                <div class="col-md-8 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="d-flex col-md-8" style="flex-direction: column" method="post"
                  action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="article" class="form-label">Артикул</label>
                    <input type="text" class="form-control @error('article') is-invalid @enderror"
                           id="article" name="article" value="{{ old('article') }}" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Название товара</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Цена</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                               id="price" name="price" value="{{ old('price') }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="discount" class="form-label">Скидка %</label>
                        <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror"
                               id="discount" name="discount" value="{{ old('discount', 0) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Количество</label>
                        <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror"
                               id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="supplier_id" class="form-label">Поставщик</label>
                        <select class="form-control @error('supplier_id') is-invalid @enderror"
                                id="supplier_id" name="supplier_id" required>
                            <option value="">Выберите поставщика</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label">Категория</label>
                        <select class="form-control @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">Выберите категорию</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="manufacturer_id" class="form-label">Производитель</label>
                        <select class="form-control @error('manufacturer_id') is-invalid @enderror"
                                id="manufacturer_id" name="manufacturer_id" required>
                            <option value="">Выберите производителя</option>
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->id }}" {{ old('manufacturer_id') == $manufacturer->id ? 'selected' : '' }}>
                                    {{ $manufacturer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" onchange="previewImage(this)">
                    <div id="imagePreview" class="mt-2"></div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Добавить товар</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.className = 'img-thumbnail';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
