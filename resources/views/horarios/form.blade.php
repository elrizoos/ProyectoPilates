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
           <label for="diaSemana0">Día de la semana:</label>
           <select name="diaSemana[]" id="diaSemana0" required>
               <option value="Lunes">Lunes</option>
               <option value="Martes">Martes</option>
               <option value="Miércoles">Miércoles</option>
               <option value="Jueves">Jueves</option>
               <option value="Viernes">Viernes</option>
           </select>
           <button type="button" id="agregarDia">+</button>
       </div>

       <!-- Tramos horarios establecidos:
            Tramo 1: 10:00 --- 11:20
            Tramo 2: 11:30 --- 12:50
            Tramo 3: 13:00 --- 14:20
            Tramo 4: 15:00 --- 16:20
            Tramo 5: 16:30 --- 17:50
            Tramo 6: 18:00 --- 19:20
            Tramo 7: 19:30 --- 20:50

       -->
       <div class="form-group">
           <select name="tramoHorario" id="tramoHorario">
               <option value="default">Elige un tramo horario</option>
               <option value="10:00 --- 11:20">10:00 --- 11:20</option>
               <option value="11:30 --- 12:50">11:30 --- 12:50</option>
               <option value="13:00 --- 14:20">13:00 --- 14:20</option>
               <option value="15:00 --- 16:20">15:00 --- 16:20</option>
               <option value="16:30 --- 17:50">16:30 --- 17:50</option>
               <option value="18:00 --- 19:20">18:00 --- 19:20</option>
               <option value="19:30 --- 20:50">19:30 --- 20:50</option>
           </select>
       </div>

       <input type="hidden" name="horaInicio" id="horaInicio">
       <input type="hidden" name="horaFin" id="horaFin">

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
