@extends('layouts.app')

@section('title')Список заказов@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        @if($role_id === 1 || $role_id === 2)
                            <div class="card mb-3">
                                <div class="card-body">
                                    @if($role_id === 1)
                                        <a href="/" class="btn btn-primary">Добавить заказ</a>
                                        <button id="editButton" class="btn btn-primary">Редактировать заказ</button>
                                        <button id="deleteButton" class="btn btn-primary">Удалить заказ</button>
                                    @endif
                                    <a href="{{route('products')}}" class="btn btn-primary">Товары</a>
                                </div>
                            </div>
                        @endif

                        @foreach($orders as $order)
                        <div class="card mb-3 order-card" onclick="selectOrder(this,{{$order->id}})">
                            <div class="row g-0 card-body">
                                <div class="d-flex">
                                    <div class="border-dark border-1 border" style="width: 60%">
                                        <div class="card-body row">
                                            <span>ФИО клиента: {{$order->user->surname}} {{$order->user->name}} {{$order->user->patronymic}}</span>
                                            <span>Статус заказа: {{$order->status->name}}</span>
                                            <span>Адрес пункта выдачи: {{$order->pickup_point->name}}</span>
                                            <span>Дата заказа: {{$order->date_order}}</span>
                                        </div>
                                    </div>
                                    <div class="border-dark border-1 border" style="width: 35%; margin-left: 5%">
                                        <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%">
                                            <span>Дата доставки: {{$order->date_delivery}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-dark border-1 border" style="margin-top: 5%">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Артикул</th>
                                            <th scope="col">Название-товара</th>
                                            <th scope="col">Количество на складе</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->details as $detail)
                                        <tr>
                                            <td>{{$detail->product->article}}</td>
                                            <td>{{$detail->product->name}}</td>
                                            <td>{{$detail->quantity}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" id="deleteForm">
        @csrf
        @method('delete')
    </form>

    <style>
        .order-card{
            border: 1px solid black;
        }
        .order-card.selected{
            border: 1px solid red;
        }
    </style>

    <script>
        let selectedOrderId = null;

        function selectOrder(card, id){
            document.querySelectorAll('.order-card').forEach(c =>
            c.classList.remove('selected'));

            if (selectedOrderId === id){
                selectedOrderId = null;
                return;
            }

            card.classList.add('selected');
            selectedOrderId = id;
        }

        document.getElementById('deleteButton').onclick = () =>{
            if(selectedOrderId && confirm('Удалить заказ?')){
                const form = document.getElementById('deleteForm');
                form.action = `/orders/${selectedOrderId}`;
                form.submit();
            }
        }
    </script>
@endsection
