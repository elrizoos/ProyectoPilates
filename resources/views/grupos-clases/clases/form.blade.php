       <h1>{{ $modo }} clase</h1>

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
               id="nombre"value="{{ isset($clase->nombre) ? $clase->nombre : old('nombre') }}">
       </div>
       <div class="form-group">
           <label for="nivel">Nivel </label>
           <input class="form-control" type="text" name="nivel"
               id="nivel"value="{{ isset($clase->nivel) ? $clase->nivel : old('nivel') }}">
       </div>
      
       
       <div class="form-group">
           <input class="btn btn-success" type="submit" value="{{ $modo }} datos clase">

           <a class="btn btn-primary" href="{{ url('grupos-clases/clases/') }}">Regresar</a>
       </div>
