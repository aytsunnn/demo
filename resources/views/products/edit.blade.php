@extends('layouts.app')

@section('title')Редактирование товара@endsection

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
                  action="{{ route('products.update', $product->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="article" class="form-label">Артикул</label>
                    <input type="text" class="form-control @error('article') is-invalid @enderror"
                           id="article" name="article" value="{{ old('article', $product->article) }}" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Название товара</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Цена</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="discount" class="form-label">Скидка %</label>
                        <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror"
                               id="discount" name="discount" value="{{ old('discount', $product->discount) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Количество</label>
                        <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror"
                               id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="supplier_id" class="form-label">Поставщик</label>
                        <select class="form-control @error('supplier_id') is-invalid @enderror"
                                id="supplier_id" name="supplier_id" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label">Категория</label>
                        <select class="form-control @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="manufacturer_id" class="form-label">Производитель</label>
                        <select class="form-control @error('manufacturer_id') is-invalid @enderror"
                                id="manufacturer_id" name="manufacturer_id" required>
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->id }}"
                                    {{ old('manufacturer_id', $product->manufacturer_id) == $manufacturer->id ? 'selected' : '' }}>
                                    {{ $manufacturer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Изображение</label>

                    @if($product->image_path)
                        <div id="currentImageContainer" class="mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('assets/images/' . $product->image_path) }}"
                                     alt="{{ $product->name }}"
                                     style="max-width: 150px; max-height: 150px;"
                                     class="img-thumbnail">
                                <button type="button" class="btn btn-danger" onclick="removeCurrentImage()">
                                    Удалить фото
                                </button>
                            </div>
                        </div>
                    @endif

                    <div id="newImageSection" style="{{ $product->image_path ? 'display: none;' : '' }}">
                        <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" onchange="previewNewImage(this)">
                    </div>

                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                    <div id="newImagePreview" class="mt-2"></div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </form>
        </div>
    </div>

    <script>
        function previewNewImage(input) {
            const preview = document.getElementById('newImagePreview');
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    img.className = 'img-thumbnail';
                    preview.appendChild(img);

                    const message = document.createElement('p');
                    message.className = 'text-success mt-1';
                    message.textContent = 'Новое изображение будет загружено при сохранении';
                    preview.appendChild(message);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeCurrentImage() {
            if (confirm('Вы уверены, что хотите удалить изображение?')) {
                document.getElementById('currentImageContainer').style.display = 'none';
                document.getElementById('newImageSection').style.display = 'block';
                document.getElementById('remove_image').value = '1';
            }
        }
    </script>
@endsection
