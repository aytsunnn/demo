@extends('layouts.app')

@section('title')Создание товара@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body">

                        <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="first" class="form-label">Id</label>
                                <input readonly type="number" class="form-control @error('first') is-invalid @enderror" id="first" value="{{$product->id}}">
                            </div>
                            <div class="mb-3">
                                <label for="article" class="form-label">Артикул</label>
                                <input type="text" class="form-control @error('article') is-invalid @enderror" id="article" name="article" value="{{old('article', $product->article)}}">
                                @error('article')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name', $product->name)}}">
                                @error('name')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="number" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{old('price', $product->price)}}">
                                @error('price')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="seller_id" class="form-label">Поставщик</label>
                                <select class="form-select @error('seller_id') is-invalid @enderror" id="seller_id" name="seller_id">
                                    <option value="">Выберите поставщика</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{$seller->id}}" {{old('seller_id', $product->seller_id) == $seller->id ? 'selected' : ''}}>
                                            {{$seller->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('seller_id')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="manufacturer_id" class="form-label">Производитель</label>
                                <select class="form-select @error('manufacturer_id') is-invalid @enderror" id="manufacturer_id" name="manufacturer_id">
                                    <option value="">Выберите производителя</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{$manufacturer->id}}" {{old('manufacturer_id', $product->manufacturer_id) == $manufacturer->id ? 'selected' : ''}}>
                                            {{$manufacturer->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('manufacturer_id')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Категория</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">Выберите категорию</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{old('category_id', $product->category_id) == $category->id ? 'selected' : ''}}>
                                            {{$category->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label">Скидка</label>
                                <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{old('discount', $product->discount)}}">
                                @error('discount')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Количество на складе</label>
                                <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{old('quantity', $product->quantity)}}">
                                @error('quantity')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{old('description', $product->description)}}</textarea>
                                @error('description')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Фото</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{old('image', $product->image_path)}}" onchange="previewImage(this)">
                                <input type="hidden" id="remove_image" name="remove_image" value="0">

                                <div id="imagePreview" class="mt-2">
                                    @if($product->image_path)
                                        <div>
                                            <img src="{{asset('assets/images/'.$product->image_path)}}" class="img-thumbnail" style="width: 200px; height: 200px">
                                            <button type="button" class="btn btn-danger" onclick="removeImage()">Удалить фото</button>
                                        </div>
                                    @else
                                        <img src="{{asset('assets/images/picture.png')}}" class="img-thumbnail" style="width: 200px; height: 200px">
                                    @endif
                                </div>
                                @error('image')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input){
            const preview = document.getElementById('imagePreview');
            const flag = document.getElementById('remove_image');

            if (input.files && input.files[0]){
                const reader = new FileReader();
                reader.onload = function (e){
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="width: 200px; height: 200px">`;
                    flag.value = '0';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage(){
            const image = document.getElementById('imagePreview');
            const preview = document.getElementById('imagePreview');
            const flag = document.getElementById('remove_image');

            flag.value = '1';
            preview.innerHTML = `<img src="{{asset('assets/images/picture.png')}}" class="img-thumbnail" style="width: 200px; height: 200px">`;
            image.value = '';
        }
    </script>
@endsection
