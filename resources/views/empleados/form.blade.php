       <h1>{{ $modo }} empleado</h1>

       {{-- Para poder mostrar los mensajes de error --}}

       @if (count($errors) > 0)
           <div class="alert alert-danger" role="alert">
               <ul>
                   @foreach ($errors->all() as $error)
                       <li> {{ $error }}</li>
                   @endforeach
               </ul>
           </div>
       @endif


       <div class="col col-10">
           <div class="row row-12">
               <div class="form-group col col-8">
                   <label for="nombre">Nombre: </label>
                   <input class="form-control" type="text" name="nombre"
                       id="nombre"value="{{ isset($empleado->nombre) ? $empleado->nombre : old('nombre') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="apellidos">Apellidos: </label>
                   <input class="form-control" type="text" name="apellidos" id="apellidos"
                       value="{{ isset($empleado->apellidos) ? $empleado->apellidos : old('apellidos') }}">
               </div>
           </div>
           <div class="row row-12">
               <div class="form-group col col-8">
                   <label for="dni">DNI: </label>
                   <input class="form-control" type="text" name="dni" id="dni"
                       value="{{ isset($empleado->dni) ? $empleado->dni : old('dni') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="telefono">Telefono: </label>
                   <input class="form-control" type="text" name="telefono" id="telefono"
                       value="{{ isset($empleado->telefono) ? $empleado->telefono : old('telefono') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="email">Email: </label>
                   <input class="form-control" type="text" name="email" id="email"
                       value="{{ isset($empleado->email) ? $empleado->email : old('email') }}">
                    </div>
               <div class="form-group col col-4">
                   <label for="fechaNacimiento">Fecha Nacimeinto: </label><br>
                   <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                       value="{{ isset($empleado->fechaNacimiento) ? $empleado->fechaNacimiento : old('fechaNacimiento') }}">
               </div>
           </div>
           <div class="row row-12">
               <div class="form-group col col-4">
                   <label for="direccion">Direccion: </label>
                   <input class="form-control" type="text" name="direccion" id="dni"
                       value="{{ isset($empleado->direccion) ? $empleado->direccion : old('direccion') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="clase">Clase: </label>
                   <input class="form-control" type="text" name="clase" id="clase"
                       value="{{ isset($empleado->clase) ? $empleado->clase : old('clase') }}">
               </div>

           </div>


       </div>

       <div class="col col-2">

           <div class="form-group form-foto">
               <label for="foto"> </label>
               @if (isset($empleado->foto))
                   <img class="img-thumbnail img-fluid" width="100px" height="100px"
                       src="{{ asset('storage') . '/' . $empleado->foto }}" alt="Imagen del usuario">
               @endif
           </div>
           <div class="form-group">
               <input class="form-control" type="file" name="foto" id="foto">
           </div>
           <div class="form-group">
               <input class="btn btn-success" type="submit" value="{{ $modo }} datos empleado">

               <a class="btn btn-primary" href="{{ url('empleados/') }}">Regresar</a>
           </div>
       </div>
