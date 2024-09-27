<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/adminlte.min.css">
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
        background-color: #f9f9f9; /* Color de fondo */
    }

    .content {
        text-align: center;
    }

    .error-page {
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 10px;
    }

    .headline {
        font-size: 80px;
        margin-bottom: 20px;
        color: #dc3545; /* Color rojo para el número de error */
    }

    .error-content {
        margin-top: 20px;
    }

    .error-content h3 {
        font-size: 24px;
        color: #dc3545; /* Color rojo para el mensaje de error */
    }

    .error-content p {
        font-size: 16px;
        color: #6c757d; /* Color de texto gris oscuro */
    }

    .error-content a {
        color: #007bff; /* Color azul para los enlaces */
        text-decoration: none;
        font-weight: bold;
    }

    .error-content a:hover {
        text-decoration: underline;
    }

    .navbar-nav.ml-auto {
        margin-right: 20px;
    }
</style>
<section class="content">
    <div class="error-page text-center">
        <div>
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Acceso denegado.</h3>

            <p>
                No tienes los permisos necesarios para acceder a esta pantalla.
                Mientras tanto, puedes <a href="<?php echo base_url; ?>Terminal/index">volver al panel de control</a> o contactar al administrador.
            </p>
        </div>

        <div class="mt-4">
            <a href="<?php echo base_url; ?>Usuarios/salir" class="btn btn-danger">
                <i class="fas fa-power-off mr-2"></i> Cerrar Sesión
            </a>
        </div>

        <div class="go-back">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
    </div>
</section>