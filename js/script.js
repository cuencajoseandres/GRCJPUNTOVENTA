$(document).ready(function() {

    $('#btnSend').click(function(e) {
        e.preventDefault(); // Evita que el formulario se envíe automáticamente

        var errores = '';

        // Validar usuario
        if ($('#usuario').val() === '') {
            errores += '<p>✖ Usuario requerido</p>';
        }

        // Validar contraseña
        if ($('#contrasena').val() === '') {
            errores += '<p>✖ Contraseña requerida</p>';
        }

        // Mostrar modal si hay errores
        if (errores != '') {
            var mensajeModal = '<div class="modal_wrap">' +
                '<div class="mensaje_modal">' +
                '<h3>Ingrese los datos:</h3>' +
                errores +
                '<span id="btnClose">Cerrar</span>' +
                '</div>' +
                '</div>';

            $('body').append(mensajeModal);
        }

        // Cerrar modal
        $(document).on('click', '#btnClose', function() {
            $('.modal_wrap').remove();
        });
    });
});
