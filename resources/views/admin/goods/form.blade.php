@extends('admin.layouts.app')
@section('custom_styles')
    <link rel="stylesheet" href="{{asset('build/assets/dropzone.css')}}">
@endsection
@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="d-flex justify-content-between align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Tovarlar
                    </div>
                    <h2 class="page-title">
                        @if($good->exists())
                            Tahrirlash - {{$good->name}}
                        @else
                            Yangi tovar yaratish
                        @endif
                    </h2>
                </div>
                <a href="{{route('admin.goods.index')}}" class="btn btn-primary">Barcha Tovarlar</a>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <form  action="{{route('admin.goods.save', ['good' => $good->id])}}" method="POST" class="card" enctype="multipart/form-data" autocomplete="off">
                @csrf @method('POST')

                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-label">Nomi</div>
                        <input type="text" name="name" value="{{$good->name}}" required min="3" placeholder="SALOX 5 kVt" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-label">Kategoriya</div>
                        <select name="category_id" class="form-select" required>
                            <option value="999" @if(in_array($good->category_id, [null, 999])) selected @endif >Xar xil</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @selected($good->category_id === $category->id)>{{$category->title}}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <div class="mx-auto w-100">
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input value="1" @if($good->status === null || $good->status) checked @endif name="status" class="form-check-input" type="checkbox">
                                    <span class="form-check-label">Aktiv</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Rasm yuklang</h3>
                                    <div class="dropzone" id="good-image">
                                        <div class="fallback">
                                            <input type="file" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input class="visually-hidden" name="image" id="image-input" type="file"  />
                        </div>
                        @if($good->image)
                            <div class="col-md-6 px-4">
                                <img src="{{asset($good->image)}}" alt="" class="d-block mw-100 mx-auto">
                            </div>
                        @endif
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                </div>

            </form>

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let myDropzone = new Dropzone('#good-image', {
                url: "/upload",  // Это не будет использовано, так как отправка через форму
                autoProcessQueue: false,  // Не отправляем файлы сразу
                maxFilesize: 2,  // Максимальный размер файла (например, 2MB)
                addRemoveLinks: true,  // Добавляем возможность удаления файлов
                dictDefaultMessage: 'Перетащите файлы сюда или кликните для выбора',
                dictRemoveFile: 'Удалить файл',
                acceptedFiles: 'image/*',  // Принимаем только изображения
            });

            let fileInput = document.getElementById('image-input');
            myDropzone.on("addedfile", function(file) {
                // Используем DataTransfer для имитации файлового ввода
                let dataTransfer = new DataTransfer();
                myDropzone.files.forEach(function(file) {
                    dataTransfer.items.add(file);  // Добавляем файл в DataTransfer
                });

                // Обновляем содержимое скрытого input с новыми файлами
                fileInput.files = dataTransfer.files;
            });

            myDropzone.on("removedfile", function(file) {
                let dataTransfer = new DataTransfer();  // Пересоздаем DataTransfer для обновления
                myDropzone.files.forEach(function(file) {
                    dataTransfer.items.add(file);  // Добавляем оставшиеся файлы
                });

                // Обновляем содержимое скрытого input
                fileInput.files = dataTransfer.files;
            });
        });
    </script>
@endsection
@section('custom_scripts')
    <script src="{{asset('build/assets/dropzone.js')}}"></script>
@endsection
