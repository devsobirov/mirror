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
                        Tariflar
                    </div>
                    <h2 class="page-title">
                        @if($service->id)
                            Tahrirlash - {{$service->full_title}}
                        @else
                            Yangi tarif yaratish
                        @endif
                    </h2>
                </div>
                <a href="{{route('admin.services.index')}}" class="btn btn-primary">Barcha tariflar</a>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <form  action="{{route('admin.services.save', ['service' => $service->id])}}" method="POST" class="card" enctype="multipart/form-data" autocomplete="off">
                @csrf @method('POST')

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="form-label">Xizmat turini tanlang</div>
                            <select name="category_id" class="form-select" required>
                                <option value="">Tanlang</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @selected($service->category_id === $category->id)>{{$category->title}}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="form-label">Nomi</div>
                            <input type="text" name="name" value="{{$service->name}}" required min="3" placeholder="SALOX 5 kVt" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="form-label">Kalkulyator turini tanlang</div>
                            <select name="calculator_id" class="form-select">
                                <option value="">Tanlang</option>
                                @foreach(App\Enums\Service\ServiceCalculatorTypeEnum::list() as $value => $name)
                                    <option value="{{$value}}" @selected($service->calculator_id === $value)>{{$name}}</option>
                                @endforeach
                            </select>
                            @error('calculator_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label class="form-label">Xizmat birligi nomi</label>
                            <div class="input-group">
                                <input type="text" name="unit" class="form-control" value="{{$service->unit}}" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">1 xizmat birligi uchun narx (UZS)</label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control" min="0" value="{{$service->price}}">
                                <button type="button" class="btn">UZS</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Ustama narx (%)</label>
                            <div class="input-group">
                                <input type="number" name="margin" class="form-control" min="0" value="{{$service->margin}}">
                                <button type="button" class="btn">%</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-check form-switch">
                                <input value="1" @if($service->active === null || $service->active) checked @endif name="active" class="form-check-input" type="checkbox">
                                <span class="form-check-label">Aktiv</span>
                            </label>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_sub_service" @checked($service->is_sub_service) value="1">
                                <span class="form-check-label">Qo'shimcha xizmat bo'la oladi</span>
                            </label>
                        </div>
                    </div>
                    @if(count($availableServices))
                    <div class="col-lg-12 mb-3">
                            <div class="form-label">Qo'shimcha xizmatlar</div>
                            <div class="form-selectgroup form-selectgroup-boxes d-flex">
                                @foreach($availableServices as $subService)
                                    <label class="form-selectgroup-item">
                                        <input type="checkbox" name="sub_services[]" value="{{ $subService->id }}" class="form-selectgroup-input"
                                            @checked($service->subServices->contains($subService->id))>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <span class="avatar me-2" style="background-image: url({{ $subService->image_url }})"></span>
                                                {{ $subService->name }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('sub_services')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
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
                        @if($service->image)
                            <div class="col-md-6 px-4">
                                <img src="{{asset($service->image)}}" alt="" class="d-block mw-100 mx-auto">
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
