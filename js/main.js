jQuery(document).on('submit', '#formLg', function (event) {
    event.preventDefault();

    let $form = $(this);
    let $submitBtn = $form.find('button[type="submit"], input[type="submit"]');

    $submitBtn
        .prop('disabled', true)
        .html('<i class="fas fa-spinner fa-spin"></i> Verificando...');

    $.ajax({
        url: 'validacion_login/val_login.php',
        type: 'POST',
        dataType: 'json',
        data: $form.serialize()
    })
    .done(function (respuesta) {

        if (typeof respuesta !== 'object') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Respuesta inválida del servidor.'
            });
            return;
        }

        if (respuesta.error === false) {

            let tipo = Number(respuesta.tipo_usuario);
            let usuario = respuesta.usuario || 'Usuario';
            let rol = respuesta.rol || '';

            // 🎉 ALERTA DE BIENVENIDA
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                html: `
                    <b>${usuario}</b><br>
                    <small>${rol}</small><br><br>
                    <span>Bienvenido al sistema de gestión CJ Informática</span>
                `,
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });

            // ⏳ Redirección luego del mensaje
            setTimeout(() => {
                switch (tipo) {
                    case 1: // ADMIN
                    case 2: // EMPRESA
                    case 3: // EMPLEADO
                        window.location.href = 'vista/empresa/inicio_empresa.php';
                        break;

                    case 4: // CLIENTE
                        window.location.href = 'index.php';
                        break;

                    default:
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: 'Tipo de usuario no reconocido.'
                        });
                }
            }, 1800);

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error de login',
                text: respuesta.mensaje || 'Credenciales incorrectas.'
            });
        }
    })
    .fail(function (resp) {
        console.error('Error AJAX:', resp.responseText);

        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'No se pudo conectar con el servidor.',
            confirmButtonColor: '#e74a3b'
        });
    })
    .always(function () {
        $submitBtn
            .prop('disabled', false)
            .html('<i class="fas fa-sign-in-alt"></i> Iniciar Sesión');
    });
});
