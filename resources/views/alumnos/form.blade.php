       <h1>{{ $modo }} alumno</h1>

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
                       id="nombre"value="{{ isset($alumno->nombre) ? $alumno->nombre : old('nombre') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="apellidos">Apellidos: </label>
                   <input class="form-control" type="text" name="apellidos" id="apellidos"
                       value="{{ isset($alumno->apellidos) ? $alumno->apellidos : old('apellidos') }}">
               </div>
           </div>
           <div class="row row-12">
               <div class="form-group col col-8">
                   <label for="dni">DNI: </label>
                   <input class="form-control" type="text" name="dni" id="dni"
                       value="{{ isset($alumno->dni) ? $alumno->dni : old('dni') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="telefono">Telefono: </label>
                   <input class="form-control" type="text" name="telefono" id="telefono"
                       value="{{ isset($alumno->telefono) ? $alumno->telefono : old('telefono') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="email">Email: </label>
                   <input class="form-control" type="text" name="email" id="email"
                       value="{{ isset($alumno->email) ? $alumno->email : old('email') }}">
                    </div>
               <div class="form-group col col-4">
                   <label for="fechaNacimiento">Fecha Nacimeinto: </label><br>
                   <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                       value="{{ isset($alumno->fechaNacimiento) ? $alumno->fechaNacimiento : old('fechaNacimiento') }}">
               </div>
           </div>
           <div class="row row-12">
               <div class="form-group col col-4">
                   <label for="direccion">Direccion: </label>
                   <input class="form-control" type="text" name="direccion" id="dni"
                       value="{{ isset($alumno->direccion) ? $alumno->direccion : old('direccion') }}">
               </div>
               <div class="form-group col col-4">
                   <label for="grupo">Grupo: </label>
                   <input class="form-control" type="text" name="grupo" id="grupo"
                       value="{{ isset($alumno->grupo) ? $alumno->grupo : old('grupo') }}">
               </div>

           </div>


       </div>

       <div class="col col-2">

           <div class="form-group form-foto">
               <label for="foto"> </label>
               @if (isset($alumno->foto))
                   <img class="img-thumbnail img-fluid" width="100px" height="100px"
                       src="{{ asset('storage') . '/' . $alumno->foto }}" alt="Imagen del usuario">
               @endif
           </div>
           <div class="form-group">
               <input class="form-control" type="file" name="foto" id="foto">
           </div>
           <div class="form-group">
               <input class="btn btn-success" type="submit" value="{{ $modo }} datos alumno">

               <a class="btn btn-primary" href="{{ url('alumnos/') }}">Regresar</a>
           </div>
       </div>
