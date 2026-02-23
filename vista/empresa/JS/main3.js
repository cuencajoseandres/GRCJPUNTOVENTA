$(document).ready(function() {
    tablapedido = $("#Asignarcadete").DataTable({
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnoferta'>Finalizar tarea</button>"
        }],

        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        }
    });


    var fila; //capturar la fila para editar o borrar el registro

    //botón enviar oferta    
    $(document).on("click", ".btnoferta", function() {
        fila = $(this).closest("tr");
        id_oferta = parseInt(fila.find('td:eq(0)').text());
        cliente = fila.find('td:eq(1)').text();
        mensaje = fila.find('td:eq(2)').text();
        monto = fila.find('td:eq(3)').text();
        duracion = fila.find('td:eq(4)').text();
        Nro_Cadete = fila.find('td:eq(5)').text();

        $("#id_oferta").val(id_oferta);
        $("#cliente").val("Cliente: " + cliente + " Mensaje: " + mensaje + "Monto: " + monto);
        $("#Nro_Cadete").val(Nro_Cadete);


        $(".modal-header").css("background-color", "#4e73df");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Finalizar Tarea del empleado");
        $("#modalCRUD").modal("show");

    });




});