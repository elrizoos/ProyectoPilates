       <h1>{{ $modo }} grupo</h1>

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


       <div class="form-group">
           <label for="nombre">Nombre: </label>
           <input class="form-control" type="text" name="nombre"
               id="nombre"value="{{ isset($grupo->nombre) ? $grupo->nombre : old('nombre') }}">
       </div>
       <div class="form-group">
           <label for="nombre">Maximo Participantes: </label>
           <input class="form-control" type="text" name="maxParticipantes"
               id="maxParticipantes"value="{{ isset($grupo->maxParticipantes) ? $grupo->maxParticipantes : old('maxParticipantes') }}">
       </div>
       <div class="form-group">
           <label for="nombre">codigoClase: </label>
           <input class="form-control" type="text" name="codigoClase"
               id="codigoClase"value="{{ isset($grupo->codigoClase) ? $grupo->codigoClase : old('codigoClase') }}">
       </div>
       
       <div class="form-group">
           <input class="btn btn-success" type="submit" value="{{ $modo }} datos grupo">

           <a class="btn btn-primary" href="{{ url('grupos-clases/grupos/') }}">Regresar</a>
       </div>
