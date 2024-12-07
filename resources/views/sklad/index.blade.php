@extends('admin.layouts.app')

@section('custom_styles')
    <link rel="stylesheet" href="{{asset('build/assets/dropzone.css')}}">
@endsection
@php
    $isAdmin = auth()->user()->isAdmin();
    auth()->user()->load('warehouses');
    $warehousesIn = $isAdmin ? $warehouses
        : auth()->user()->warehouses->where('active', true)->where('pivot.action', \App\Models\Warehouse\Warehouse::ACTION_IN)->pluck('name', 'id')->toArray();
    $warehousesOut = $isAdmin ? $warehouses
        : auth()->user()->warehouses->where('active', true)->where('pivot.action', \App\Models\Warehouse\Warehouse::ACTION_OUT)->pluck('name', 'id')->toArray();
@endphp
@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Tovarlar ({{$goods->total()}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('sklad.index')}}" class="d-md-flex d-sm-block align-items-center">
                    <select class="form-select mr-2" name="category_id">
                        <option value="">Barchasi</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if(request('category_id') == $category->id) selected @endif>{{$category->title}}</option>
                        @endforeach
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="Tovar nomi ..." name="search" value="{{request('search')}}">
                    <button class="btn me-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                    @if(auth()->user()->isAdmin())
                        <button type="button" class="btn me-1 btn-icon btn-green" data-bs-toggle="modal" data-bs-target="#modal-balance-export">
                            <x-svg.excel></x-svg.excel>
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>Tovar</th>
                            <th>Qoldiq (jami)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $good)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a data-fslightbox href="{{$good->image_url}}">
                                            <span class="avatar" style="background-image: url({{$good->image_url}})"></span>
                                        </a>
                                        <p style="font-weight: 500" class="m-0">{{ $good->full_title }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-azure">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $good->current_amount }} dona
                                    </span>
                                </td>
                                <td>
                                    <div class="d-sm-flex flex-col m-lg-auto" style="gap: 6px;">
                                        <a href="{{route('sklad.good', ['good' => $good->id])}}"  class="btn btn-info d-sm-block">
                                            <x-svg.report-search></x-svg.report-search> <span class="ml-1">Batafsil</span>
                                        </a>
                                        <div class="d-lg-none d-sm-block p-1"></div>
                                        @if(count($warehousesIn))
                                            <a href="#" class="btn btn-success d-sm-block"
                                               data-bs-toggle="modal" data-bs-target="#modal-income-{{$good->id}}"
                                            >
                                                <x-svg.plus></x-svg.plus> <span class="ml-1">Kirim</span>
                                            </a>
                                            @include('sklad.modal-income', ['good' => $good, 'isAdmin' => $isAdmin])
                                        @else
                                            <a href="#" class="btn btn-success d-sm-block disabled">
                                                <x-svg.plus></x-svg.plus> <span class="ml-1">Kirim</span>
                                            </a>
                                        @endif
                                        <div class="d-lg-none d-sm-block p-1"></div>
                                        @if($good->current_amount > 0 && count($warehousesOut) > 0 && $good->isInStock(array_keys($warehousesOut)))
                                            <a href="#" class="btn btn-danger d-sm-block"
                                               data-bs-toggle="modal" data-bs-target="#modal-expense-{{$good->id}}"
                                            >
                                                <x-svg.minus></x-svg.minus> <span class="ml-1">Chiqim</span>
                                            </a>
                                            @include('sklad.modal-expense',['good' => $good, 'isAdmin' => $isAdmin])
                                        @else
                                            <a href="#" class="btn btn-danger d-sm-block disabled cursor-not-allowed">
                                                <x-svg.minus></x-svg.minus> <span class="ml-1">Chiqim</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if( $goods->hasPages() )
                    <div class="card-footer pb-0">
                        {{ $goods->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if(auth()->user()->isAdmin())
        @include('sklad.modal-balance-export')
    @endif
    <script>
        const _warehouses = @json($warehousesOut);
        function expenseModalData() {
            return {
                ws: [],
                warehouse_id: null,
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
                    this.maxAmount = selectedWarehouse ? selectedWarehouse.amount : 0;
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
