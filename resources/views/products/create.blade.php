@extends('layouts.app')

@section('title')Создание товара@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body">

                        <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="article" class="form-label">Артикул</label>
                                <input type="text" class="form-control @error('article') is-invalid @enderror" id="article" name="article" value="{{old('article')}}">
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name')}}">
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
                                <input type="number" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{old('price')}}">
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
                                        <option value="{{$seller->id}}" {{old('seller_id') == $seller->id ? 'selected' : ''}}>
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
                                        <option value="{{$manufacturer->id}}" {{old('manufacturer_id') == $manufacturer->id ? 'selected' : ''}}>
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
                                        <option value="{{$category->id}}" {{old('category_id') == $category->id ? 'selected' : ''}}>
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
                                <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{old('discount')}}">
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
                                <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{old('quantity')}}">
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{old('description')}}</textarea>
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
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{old('image')}}" onchange="previewImage(this)">
                                <div id="imagePreview" class="mt-2">
                                    <img src="{{asset('assets/images/picture.png')}}" class="img-thumbnail" style="width: 200px; height: 200px">
                                </div>
                                @error('image')
                                <div class="alert alert-danger d-flex align-items-center mt-2" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" style="width: 20px; height: 20px" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        {{$message}}
                                    </div>
                                </div>
                                @enderror
                                <div class="alert alert-primary d-flex align-items-center mt-2" id="info" role="alert">
                                    <svg class="bi flex-shrink-0 me-2 mt-2" style="width: 20px; height: 20px;" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                                    <div>
                                        Новое изображение будет добавлено после добавления товара
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input){
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]){
                const reader = new FileReader();
                reader.onload = function (e){
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="width: 200px; height: 200px">`
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
