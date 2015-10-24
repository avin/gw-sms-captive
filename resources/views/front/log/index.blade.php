@extends('layouts.master')

@section('content')


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


@stop