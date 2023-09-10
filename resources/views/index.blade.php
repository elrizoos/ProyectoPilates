<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @extends('layouts.app', ['modo' => 'Centro de Control'])
    @section('content')
        <div class="container">

            @if (Session::has('mensaje'))
                {{ Session::get('mensaje') }}
            @endif


            <div class="row">
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Alumnos</h3>
                        <ul>
                            <li>
                                <a href="{{ url('/alumnos/create') }}">Crear nuevo alumno</a>
                            </li>
                            <li>
                                <p id="mostrarPopupEdit">Gestionar alumno</p>
                                <script>
                                    document.getElementById('mostrarPopupEdit').addEventListener('click', function(){
                                        //Url donde se encuentra el contenido que voy a mostrar en la ventana emergente
                                        var url = "{{route('alumnos.search')}}";
                                        
                                        //Ancho y Alto de la ventana emergente
                                        var width = 600;
                                        var height = 400;

                                        //Opciones adicionales de la ventana emergente
                                        var opciones = 'toolbar=no, location=no, directories=no, status=no';

                                        //Abrir la ventana emergente
                                        window.open(url, 'PopupWindow', opciones);
                                    });
                                </script>
                            </li>
                            
                        </ul>
                        <a href="{{ url('alumnos/')}}"><input type="button" value="Ir a la configuración general"></a>
                    </div>
                </div>
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Empleados</h3>
                        <ul>
                            <li>Crear nuevo alumno</li>
                            <li>Editar alumno</li>
                            <li>Borrar alumno</li>
                        </ul>
                        <input type="button" value="Ir a la configuración general">
                    </div>
                </div>
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Grupos</h3>
                        <ul>
                            <li>Crear nuevo alumno</li>
                            <li>Editar alumno</li>
                            <li>Borrar alumno</li>
                        </ul>
                        <input type="button" value="Ir a la configuración general">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Clases</h3>
                        <ul>
                            <li>Crear nuevo alumno</li>
                            <li>Editar alumno</li>
                            <li>Borrar alumno</li>
                        </ul>
                        <input type="button" value="Ir a la configuración general">
                    </div>
                </div>
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Horarios</h3>
                        <ul>
                            <li>Crear nuevo alumno</li>
                            <li>Editar alumno</li>
                            <li>Borrar alumno</li>
                        </ul>
                        <input type="button" value="Ir a la configuración general">
                    </div>
                </div>
                <div class="col col-4">
                    <div class="contenedorTarjeta">
                        <h3>Control Altas</h3>
                        <ul>
                            <li>Crear nuevo alumno</li>
                            <li>Editar alumno</li>
                            <li>Borrar alumno</li>
                        </ul>
                        <input type="button" value="Ir a la configuración general">
                    </div>
                </div>
            </div>
        </div>
    @endsection
