$(document).ready(function() {
    tablapedido = $("#tablapedido").DataTable({
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnoferta'>Enviar Oferta</button>"
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


    $("#btnNuevoempleado").click(function() {
        $("#formPersonas").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Nuevo Empelado");
        $("#modalCRUD").modal("show");
    });


    var fila; //capturar la fila para editar o borrar el registro

    //botón enviar oferta    
    $(document).on("click", ".btnoferta", function() {
        fila = $(this).closest("tr");
        id_pedido = parseInt(fila.find('td:eq(0)').text());
        monto = fila.find('td:eq(1)').text();
        duracion = fila.find('td:eq(2)').text();

        $("#id_pedido").val(id_pedido);




        $(".modal-header").css("background-color", "#4e73df");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Enviar Oferta");
        $("#modalCRUD").modal("show");

    });




});