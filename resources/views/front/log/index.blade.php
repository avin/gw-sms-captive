@extends('layouts.master')

@section('content')



    <form class="form-inline" action="{{ action('LogController@index') }}" method="GET">
        <div class="form-group">
            <label for="exampleInputName2">Временной диапазон</label>
            <input type="text" class="form-control"  placeholder="Укажите время" name="daterange" value="{{ Input::get('daterange') }}" style="min-width: 280px;">
        </div>
        <button type="submit" class="btn btn-default btn-success">Показать сессии</button>
    </form>

    <hr>

    @if($sessions->count())
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Начало сессии</th>
            <th>Окончание сессии</th>
            <th>Номер телефона</th>
            <th>Лог</th>

        </tr>
        </thead>

    @foreach($sessions as $session)
        <tr>
            <td>{{ $session->created_at }}</td>
            <td>{{ $session->until }}</td>
            <td>{{ $session->key->phone_number }}</td>
            <td>
                <a href="{{ action('LogController@get', ['sessionId' => $session->id]) }}" class="btn btn-primary">Скачать</a>
            </td>
        </tr>
    @endforeach
    </table>
    @else
        Сессии не найдены. Попробуйте задать другой временной диапазон.
    @endif

    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <script src="{{ asset('assets/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />

    <script>
        $('input[name="daterange"]').daterangepicker({
            timePicker: false,
            timePickerIncrement: 30,
            locale: {
                cancelLabel: 'Очистить',
                applyLabel: 'Применить',
                format: 'MM/DD/YYYY hh:mm',
                daysOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                firstDay: 1
            }
        });

    </script>

@stop
