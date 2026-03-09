@extends('layouts.app')

@section('title')Список заказов@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Кнопки действий --}}
                <div class="mb-3 d-flex gap-2">
                    <a href="{{ route('orders.create') }}" class="btn">Создать заказ</a>
                    <button id="editButton" class="btn">Редактировать выбранный</button>
                    <button id="deleteButton" class="btn">Удалить выбранный</button>
                    <a href="{{ route('products') }}" class="btn">Товары</a>
                </div>

                {{-- Список заказов --}}
                <div id="ordersList">
                    @foreach($orders as $order)
                        <div class="card order-card mb-3"
                             id="order-{{ $order->id }}"
                             data-id="{{ $order->id }}"
                             data-text="{{ strtolower($order->id.' '.($order->status->name ?? '').' '.($order->pickupPoint->address ?? '').' '.$order->code) }}"
                             data-status="{{ strtolower($order->status->name ?? '') }}"
                             data-date="{{ $order->date_from }}"
                             data-amount="{{ $order->total_amount ?? 0 }}"
                             style="cursor: pointer"
                             onclick="selectOrder(this, {{ $order->id }})">

                                <div class="card-body d-flex gap-3">
                                    {{-- Левая колонка --}}
                                    <div class="d-flex border border-dark flex-column flex-grow-1 p-1">
                                        <p class="mb-2"><strong>{{ $order->code }}</strong></p>
                                        <p class="mb-2">{{ $order->status->name ?? 'Не указан' }}</p>
                                        <p class="mb-2">{{ $order->pickupPoint->address ?? 'Не указан' }}</p>
                                        <p class="mb-0">{{ $order->date_from ? date('d.m.Y', strtotime($order->date_from)) : 'Не указана' }}</p>
                                    </div>

                                    {{-- Правая колонка --}}
                                    <div class="d-flex border border-dark border-2 p-1 align-items-center justify-content-center">
                                        <p class="mb-2">{{ $order->date_to ? date('d.m.Y', strtotime($order->date_to)) : 'Не указана' }}</p>
                                    </div>
                                </div>
                        </div>
                    @endforeach
                </div>

                {{-- Сообщение если заказов не найдено --}}
                <div id="noOrdersMessage" class="alert alert-info" style="display: none;">
                    Заказы не найдены. Попробуйте изменить параметры поиска.
                </div>
            </div>
        </div>
    </div>

    {{-- Скрытая форма для удаления --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let selectedOrderId = null;

        // Функция выбора карточки
        function selectOrder(card, id) {
            document.querySelectorAll('.order-card').forEach(c => c.classList.remove('selected'));

            if (selectedOrderId === id) {
                selectedOrderId = null;
                return;
            }

            card.classList.add('selected');
            selectedOrderId = id;
        }

        // Обработчики кнопок
        document.getElementById('editButton')?.addEventListener('click', () => {
            if (selectedOrderId) {
                location.href = `/orders/${selectedOrderId}/edit`;
            } else {
                alert('Выберите заказ для редактирования');
            }
        });

        document.getElementById('deleteButton')?.addEventListener('click', () => {
            if (selectedOrderId && confirm('Удалить заказ?')) {
                const form = document.getElementById('deleteForm');
                form.action = `/orders/${selectedOrderId}`;
                form.submit();
            } else if (!selectedOrderId) {
                alert('Выберите заказ для удаления');
            }
        });

        // Фильтрация
        const search = document.getElementById('search');
        const status = document.getElementById('status');
        const sort = document.getElementById('sort');
        const cards = [...document.querySelectorAll('.order-card')];
        const list = document.getElementById('ordersList');

        if (search && status && sort) {
            [search, status, sort].forEach(el => el?.addEventListener('input', applyFilters));
            [search, status, sort].forEach(el => el?.addEventListener('change', applyFilters));
        }

        function applyFilters() {
            const text = search?.value.toLowerCase() || '';
            const stat = status?.value || '';
            const ord = sort?.value || '';

            let result = cards.filter(card =>
                card.dataset.text.includes(text) &&
                (!stat || card.dataset.status === stat)
            );

            if (ord) {
                result.sort((a, b) => {
                    switch(ord) {
                        case 'date_asc':
                            return new Date(a.dataset.date) - new Date(b.dataset.date);
                        case 'date_desc':
                            return new Date(b.dataset.date) - new Date(a.dataset.date);
                        case 'amount_asc':
                            return (parseFloat(a.dataset.amount) || 0) - (parseFloat(b.dataset.amount) || 0);
                        case 'amount_desc':
                            return (parseFloat(b.dataset.amount) || 0) - (parseFloat(a.dataset.amount) || 0);
                        default:
                            return 0;
                    }
                });
            }

            list.innerHTML = '';
            result.forEach(card => list.appendChild(card));

            document.getElementById('noOrdersMessage').style.display =
                result.length === 0 ? 'block' : 'none';
        }
    </script>

    <style>
        .order-card {
            border: 1px solid #dee2e6;
            transition: all 0.2s;
            border-radius: 8px;
        }
        .order-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .order-card.selected {
            border: 2px solid #D97A54 !important;
        }
        .badge {
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            padding: 4px 8px;
        }
        .card-body p {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection
