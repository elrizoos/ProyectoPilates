       <h1>{{ $modo }} Horario</h1>

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
       <!-- Lista desplegable para Clases -->
       <label for="codigoClase">Clase: </label>
       <select name="codigoClase" class="form-control">
           @foreach ($clases as $clase)
               <option value="{{ $clase->id }}">{{ $clase->id }} {{ $clase->nombre }}</option>
           @endforeach
       </select>

       <!-- Lista desplegable para Empleados -->
       <label for="codigoEmpleado">Empleado: </label>
       <select name="codigoEmpleado" class="form-control">
           @foreach ($empleados as $empleado)
               <option value="{{ $empleado->id }}">{{ $empleado->id }} {{ $empleado->nombre }}</option>
           @endforeach
       </select>

       <!-- Lista desplegable para Grupos -->
       <label for="codigoGrupo">Grupo: </label>
       <select name="codigoGrupo" class="form-control">
           @foreach ($grupos as $grupo)
               <option value="{{ $grupo->id }}">{{ $grupo->id }} {{ $grupo->nombre }}</option>
           @endforeach
       </select>
       <div class="form-group">
           <label for="diaSemana">Día de la semana:</label>
           <select name="diaSemana" id="diaSemana" required>
               <option value="Lunes">Lunes</option>
               <option value="Martes">Martes</option>
               <option value="Miércoles">Miércoles</option>
               <option value="Jueves">Jueves</option>
               <option value="Viernes">Viernes</option>
           </select>
       </div>
       <div class="form-group">
           <label for="horaInicio">Hora de inicio:</label>
           <input type="time" id="horaInicio" name="horaInicio" required>
       </div>
       <div class="form-group">
           <label for="horaFin">Hora de finalizacion:</label>
           <input type="time" id="horaFin" name="horaFin" required>
       </div>

       <div class="form-group">
           <label for="primerDia">Primer día:</label>
           <input type="date" id="primerDia" name="primerDia" required>
       </div>

       <div class="form-group">
           <label for="repetir">Repetir: </label>
           <input class="form-control" type="text" name="repetir"
               id="nivel"value="{{ isset($horario->repetir) ? $horario->repetir : old('repetir') }}">
       </div>

       <div class="form-group">
           <label for="repeticiones">Repeticiones: </label>
           <input class="form-control" type="boolean" name="repeticiones"
               id="nivel"value="{{ isset($horario->repeticiones) ? $horario->repeticiones : old('repeticiones') }}">
       </div>


       <div class="form-group">
           <input class="btn btn-success" type="submit" value="{{ $modo }} datos Horario">

           <a class="btn btn-primary" href="{{ url('horarios/') }}">Regresar</a>
       </div>
