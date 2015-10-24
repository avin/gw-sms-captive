@if($errors->any())
    <div class="alert alert-danger fade in">
        <h4>Обнаружены проблемы при вводе данных.</h4>
        <p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </p>
    </div>
@endif