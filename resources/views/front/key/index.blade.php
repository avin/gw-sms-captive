@extends('layouts.master')

@section('content')

    <div class="jumbotron">
        <h2>Рекламная инфа</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A at cupiditate dolorem dolorum ducimus eius exercitationem expedita fugiat fugit ipsa magni molestias nesciunt officiis, omnis, placeat quod ratione sed suscipit!</p>
    </div>

    @include('errors.list')
    @include('flash::message')

    {!! Former::horizontal_open()
        ->secure()
        ->action(action('KeyController@verify'))
        ->rules(['code' => 'required'])
        ->method('POST') !!}

    {!! Former::password('code')
    ->label('Ключ')
    ->autofocus() !!}

    {!! Former::actions()
    ->large_primary_submit('Верифицировать ключ') !!}

    {!! Former::close() !!}

    <hr>

    <p>Для получение ключа введите номер вашего сотового телефона ниже. Код будет выслан вам на указанный номер в виде SMS сообщения.</p>

    {!! Former::horizontal_open()
        ->secure()
        ->action(action('KeyController@getKeyProcess'))
        ->rules(['phone' => 'required'])
        ->method('POST') !!}

    {!! Former::text('phone')
    ->label('Номер телефона')
    ->placeholder('7xxxxxxxxxx') !!}

    {!! Former::actions()
    ->large_primary_submit('Выслать ключ') !!}

    {!! Former::close() !!}


@stop