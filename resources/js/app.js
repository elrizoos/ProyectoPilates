import "./bootstrap";
console.log("Script cargado");

/*$(document).ready(function () {
    $("#tramoHorario").change(function () {
        const tramo = $(this).val(); //Captura del valor del select
        console.log("tramo" + tramo);
        if (tramo) {
            const partes = tramo.split("---");
            console.log("partes:  " + partes);
            console.log(partes[0]);
            $("#horaInicio").val(partes[0]);
            $("#horaFin").val(partes[1]);
        } else {
            $("#horaInicio").val("");
            $("#horaFin").val("");
        }
    });
});
*/
$(document).ready(function () {
    let contador = 1;

    $("#agregarDia").click(function (e) {
        e.preventDefault();

        let nuevoSelect = `<label for="diaSemana${contador}">Día de la semana:</label>
                           <select name="diaSemana[]" id="diaSemana${contador}" required>
                               <option value="Lunes">Lunes</option>
                               <option value="Martes">Martes</option>
                               <option value="Miércoles">Miércoles</option>
                               <option value="Jueves">Jueves</option>
                               <option value="Viernes">Viernes</option>
                           </select>`;

        $(this).before(nuevoSelect);
        contador++;
    });
});
