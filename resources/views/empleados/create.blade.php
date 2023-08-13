<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @extends('layouts.app', ['modo' => 'Crear'])

    @section('content')
        <div class="container">
            <form class="row" action="{{ url('/empleados') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('empleados.form', ['modo' => 'Crear'])
            </form>
        </div>
    @endsection
</body>

</html>
