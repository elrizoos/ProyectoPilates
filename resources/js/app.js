import './bootstrap';
console.log('Script cargado')

function establecerHorario() {
    $(document).ready(function() {
        $('#tramoHorario').change(function() {
            const tramo = $(this).text(); //Captura del valor del select
            console.log('tramo' + tramo);
            if (tramo) {
                const partes = tramo.split('---');
                console.log('partes:  ' + partes);
                $('#horaInicio').val(partes[0]);
                $('#horaFin').val(partes[1]);
            } else {
                $('#horaInicio').val('');
                $('#horaFin').val(''); 
            }
        });
    });
}

establecerHorario();
