<?php
define("RETRO_NOMBRE_FORMATO", "El nombre debe estar formado por al menos 3 caracteres de palabra.");
define("RETRO_EMAIL_FORMATO", "El correo debe tener un formato correcto.");
define("RETRO_PASS_REPETIDO", "Las contraseñas introducidas deben ser iguales.");
define("RETRO_PASS_FORMATO", "El password debe tener una minúscula, mayúscula, dígito y carácter especial.");

$enviado = filter_has_var(INPUT_POST, 'enviar');

if ($enviado) {
    // 1. Leer datos sin validar
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW);

    // 2. Validar con filter_var()
    $errorUsuarioFormato = !filter_var($usuario, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => "/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\-]+(\s+[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\- ]+){0,5}$/"]
            ]) || mb_strlen(trim($usuario)) < 3;

    $errorEmailFormato = !filter_var($email, FILTER_VALIDATE_EMAIL);

    $errorPasswordFormato = !preg_match(
                    "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}/",
                    $password1 ?? ''
    );

    $errorPasswordNoRepetido = $password1 !== $password2;
    $errorPassword = $errorPasswordFormato || $errorPasswordNoRepetido;
    $error = $errorUsuarioFormato || $errorEmailFormato || $errorPassword;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Font Icon CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <title>Registro</title>
    </head>
    <body class="bg-info">
        <div class="container mt-5">
            <?php if (!($error ?? true)): ?> <!-- (isset($error) && !$error) -->
                <div class="alert alert-success" id="mensaje" role="alert">
                    Registro realizado con éxito
                </div>
            <?php endif ?>
            <div class="d-flex justify-content-center h-100">
                <div class="card w-50">
                    <div class="card-header">
                        <h3><i class="bi bi-gear p-2"></i>Registro</h3>
                    </div>
                    <div class="card-body">
                        <form id="registro" method="POST" action="index.php" novalidate>
                            <!-- Usuario -->
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control 
                                       <?= $enviado && isset($errorUsuarioFormato) ? ($errorUsuarioFormato ? 'is-invalid' : 'is-valid') : '' ?>"
                                       id="usuario" name="usuario" placeholder="usuario"
                                       value="<?= htmlspecialchars($usuario ?? '') ?>" minlength="3" required>
                                       <?php if ($enviado && $errorUsuarioFormato): ?>
                                    <div class="invalid-feedback">
                                        <?= RETRO_NOMBRE_FORMATO ?>
                                    </div>
                                <?php endif ?>
                            </div>

                            <!-- Contraseña -->
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control 
                                       <?= $enviado && isset($errorPasswordFormato) ? ($errorPasswordFormato ? 'is-invalid' : 'is-valid') : '' ?>"
                                       id="password1" name="password1" placeholder="contraseña" minlength="8" required
                                       value="<?= htmlspecialchars($password1 ?? '') ?>">
                                       <?php if ($enviado && $errorPasswordFormato): ?>
                                    <div class="invalid-feedback"><?= RETRO_PASS_FORMATO ?></div>
                                <?php endif ?>
                            </div>

                            <!-- Repetir contraseña -->
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control 
                                       <?= $enviado && isset($errorPasswordNoRepetido) ? ($errorPasswordNoRepetido ? 'is-invalid' : 'is-valid') : '' ?>"
                                       id="password2" name="password2" placeholder="Repite la contraseña" minlength="8" required
                                       value="<?= htmlspecialchars($password2 ?? '') ?>">
                                       <?php if ($enviado && $errorPasswordNoRepetido): ?>
                                    <div class="invalid-feedback"><?= RETRO_PASS_REPETIDO ?></div>
                                <?php endif ?>
                            </div>

                            <!-- Email -->
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control 
                                       <?= $enviado && isset($errorEmailFormato) ? ($errorEmailFormato ? 'is-invalid' : 'is-valid') : '' ?>"
                                       id="email" name="email" placeholder="e-Mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
                                       <?php if ($enviado && $errorEmailFormato): ?>
                                    <div class="invalid-feedback"><?= RETRO_EMAIL_FORMATO ?></div>
                                <?php endif ?>
                            </div>

                            <!-- Botón -->
                            <div class="text-end">
                                <input type="submit" name="enviar" value="Registrar" class="btn btn-info">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity = "sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin = "anonymous"></script>
    </body>
</html>