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
                        Chiqim
                    </div>
                    <h2 class="page-title">
                        #{{$balance->id}} - {{$balance->good?->full_title}}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <form class="card" action="{{route('sklad.expenses.update', ['balance' => $balance->id])}}"
                method="POST" enctype="multipart/form-data"
                x-data="expenseModalData()"
                x-init="
                    setWarehouses({{$balance->good?->balanceStat ?? []}});
                    initDropzone('dropzone-expense-{{$balance->good?->id}}')
                    setMaxAmount()
                "
            >
                @csrf
                <div class="card-body">
                    <label class="form-label">Skladni tanlang</label>
                    <div class="form-selectgroup-boxes row mb-3">
                        <!-- A section to select warehouse (radio buttons) -->
                        <template x-for="wItem in ws" :key="wItem.id">
                            <div class="col-lg-6" x-show="wItem.amount > 0" x-cloak>
                                <label class="form-selectgroup-item">
                                    <input
                                        type="radio"
                                        name="warehouse_id"
                                        :value="wItem.id"
                                        class="form-selectgroup-input disabled"
                                        x-model="warehouse_id"
                                        @change="setMaxAmount"
                                        :disabled="wItem.amount <= 0"
                                        required
                                    >
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title strong mb-1" x-text="`${wItem.name} (${wItem.amount} dona)`"></span>
                        </span>
                    </span>
                                </label>
                            </div>
                        </template>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Tovar</label>
                            <div class="input-group">
                                <a data-fslightbox href="{{$balance->good?->image_url}}">
                                    <span class="avatar" style="background-image: url({{$balance->good?->image_url}})"></span>
                                </a>
                                <input type="text" class="form-control" disabled value="{{$balance->good?->full_title}}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Miqdori (шт)</label>
                            <input type="number" name="amount"
                                   :placeholder="warehouse_id ? (maxAmount + ' ta mavjud') : 'Oldin skladni tanlang'"
                                   class="form-control"
                                   min="1"
                                   x-model="amount"
                                   :max="maxAmount"
                                   required
                                   :disabled="!warehouse_id"
                                   value="{{$balance->amount}}"
                                   @change="checkMaxAmount"
                            >
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Narx (summa - USD)</label>
                            <div class="input-group">
                                <input type="number" name="price_usd" class="form-control" min="0" value="{{$balance->price_usd}}">
                                <button type="button" class="btn">USD</button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Sana</label>
                                <input type="date" name="created_at" class="form-control" value="{{$balance->created_at?->format('Y-m-d') ?? now()->format('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Mijoz FIO</label>
                                <input type="text" name="client_name" value="{{$balance->client?->name}}" class="form-control" placeholder="Mijozning ism familiyasi yoki Korxona nomi">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Mijoz raqami</label>
                                <input type="text" name="phone" class="form-control" value="{{$balance->client?->phone}}" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Mijoz raqami (qo'shimcha)</label>
                                <input type="text" name="phone2" class="form-control" value="{{$balance->client?->phone2}}" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Mijoz manzili</label>
                                <input type="text" name="address" value="{{$balance->client?->address}}" class="form-control" placeholder="Mijoz manzili...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Rasmlar bo'lsa yuklang</h3>
                                    <div class="dropzone" id="dropzone-expense-{{$balance->good?->id}}">
                                        <div class="fallback">
                                            <input  type="file"  multiple  />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input class="visually-hidden" name="client_images[]" id="dropzone-expense-{{$balance->good?->id}}-i" type="file"  multiple  />
                        </div>
                        <div class="col-md-6">
                            @foreach($balance->clientImages as $image)
                                <a data-fslightbox="gallery" href="{{asset($image->filename)}}" class="d-inline-block" style="width: 200px">
                                    <img class="d-inline-block w-100" src="{{asset($image->filename)}}" alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label class="form-label">Izoh</label>
                            <textarea class="form-control" rows="2" name="description" maxlength="255">{{$balance->description}}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary ms-auto">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                        Saqlash
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script>
        const _warehouses = @json($warehouses);
        function expenseModalData() {
            return {
                ws: [],
                warehouse_id: parseInt('{{$balance->warehouse_id}}'),
                currentAmount: parseInt({{$balance->amount}}),
                amount: parseInt({{$balance->amount}}),
                maxAmount: 0,
                stat: [],
                client_images: [],
                dZone: null,
                setWarehouses(balanceStat) {
                    Object.entries(_warehouses).forEach(([id, name]) => {
                        let _w = {
                            'id': parseInt(id),
                            'name': name,
                            'amount': 0
                        }

                        if (balanceStat && balanceStat.length) {
                            balanceStat.forEach(stat => {
                                if(stat.warehouse_id === _w.id && stat.amount) {
                                    _w.amount = stat.amount;
                                }
                            })
                        }
                        this.ws.push(_w);
                    })
                    this.stat = balanceStat;
                },
                setMaxAmount() {
                    this.warehouse_id = parseInt(this.warehouse_id);
                    const selectedWarehouse = this.ws.find(item => item.id === this.warehouse_id);
                    this.maxAmount = selectedWarehouse ? (selectedWarehouse.amount + this.currentAmount) : 0;
                },
                checkMaxAmount() {
                    let amount = parseInt(this.amount);
                    if(amount > this.maxAmount) {
                        alert('Maskimal miqdor: ' +  this.maxAmount)
                        this.amount = this.currentAmount;
                    }
                }
            }
        }

        function initDropzone(elId) {
            document.addEventListener("DOMContentLoaded", function() {
                let myDropzone = new Dropzone('#'+elId, {
                    url: "/upload",  // Это не будет использовано, так как отправка через форму
                    autoProcessQueue: false,  // Не отправляем файлы сразу
                    maxFilesize: 2,  // Максимальный размер файла (например, 2MB)
                    addRemoveLinks: true,  // Добавляем возможность удаления файлов
                    dictDefaultMessage: 'Перетащите файлы сюда или кликните для выбора',
                    dictRemoveFile: 'Удалить файл',
                    acceptedFiles: 'image/*',  // Принимаем только изображения
                });

                let fileInput = document.getElementById(elId + '-i');

                // Обработчик для добавления файлов в существующий input[type="file"]
                myDropzone.on("addedfile", function(file) {
                    // Используем DataTransfer для имитации файлового ввода
                    let dataTransfer = new DataTransfer();
                    myDropzone.files.forEach(function(file) {
                        dataTransfer.items.add(file);  // Добавляем файл в DataTransfer
                    });

                    // Обновляем содержимое скрытого input с новыми файлами
                    fileInput.files = dataTransfer.files;
                });

                // Обработчик для удаления файлов из существующего input[type="file"]
                myDropzone.on("removedfile", function(file) {
                    let dataTransfer = new DataTransfer();  // Пересоздаем DataTransfer для обновления
                    myDropzone.files.forEach(function(file) {
                        dataTransfer.items.add(file);  // Добавляем оставшиеся файлы
                    });

                    // Обновляем содержимое скрытого input
                    fileInput.files = dataTransfer.files;
                });
            });
        }
    </script>
@endsection
@section('custom_scripts')
    <script src="{{asset('build/assets/dropzone.js')}}"></script>
@endsection
