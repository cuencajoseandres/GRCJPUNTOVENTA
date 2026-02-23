<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CJ INFORMATICA | Inicio</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">

    <style>
        header {
            background: linear-gradient(90deg, #1b2735, #2a5298);
            color: white;
            padding: 15px 0;
        }

        header .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
            color: #fff;
        }

        .hero {
            background: radial-gradient(circle, #1b2735, #090a0f);
            color: white;
            padding: 120px 0;
            text-align: center;
            animation: fadeIn 1.2s ease-in-out;
        }

        .hero h1 {
            font-size: 3rem;
            color: #4e73df;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #1cc88a, #4e73df);
        }

        footer {
            background: #0f2027;
            color: #bbb;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <?php include('include/header.php'); ?>


    <div class="container my-5">
        <h2>Checkout</h2>
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="alert alert-info">No hay productos para comprar.</div>
        <?php else: ?>
            <p>Implementá aquí tu pasarela de pago (MercadoPago / TodoPago / PagoFacil) o procesá la orden por cotización.</p>

            <!-- Formulario de contacto de la orden -->
            <form action="procesar_orden.php" method="post">
                <div class="form-group">
                    <label>Nombre o Razón Social</label>
                    <input type="text" name="cliente" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Correo</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <button class="btn btn-success">Enviar pedido / solicitar cotización</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include('include/footer.php'); ?>








</body>

</html>