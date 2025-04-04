<?php
define("RETRO_NOMBRE_FORMATO", "El nombre de estar formado por al menos 3 caracteres de palabra");
define("RETRO_EMAIL_FORMATO", "El correo debe tener un formato correcto");
define("RETRO_PASS_REPETIDO", "Los passwords introducidos deben de ser iguales");
define("RETRO_PASS_FORMATO", "El password debe tener una minúscula, mayúscula, digito y caracter espercial");

if (filter_has_var(INPUT_POST, 'enviar')) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW);
    $errorUsuarioFormato = (filter_var($usuario, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\-]+(\s+[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ'´`\- ]+){0,5}$/"]]) === false);
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_UNSAFE_RAW);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_UNSAFE_RAW);
    $errorPasswordNoRepetido = ($password1 !== $password2);
    $errorPasswordFormato = (filter_var($password1, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}/"]]) === false);
    $email = filter_input(INPUT_POST, 'email', FILTER_UNSAFE_RAW);
    $errorEmailFormato = (filter_var($email, FILTER_VALIDATE_REGEXP, ["options" => [
                    "regexp" => "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i"]]) === false);
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
                        <form id="registro" name="registro" action="index.php" method="POST" novalidate>
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control <?= isset($errorUsuarioFormato) ? ($errorUsuarioFormato ? "is-invalid" : "is-valid") : "" ?>"  placeholder="usuario" 
                                       id="usuario" name="usuario" value="<?= $usuario ?? '' ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?= RETRO_NOMBRE_FORMATO ?>
                                </div>
                            </div>
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control <?= isset($errorPasswordFormato) ? ($errorPasswordFormato ? "is-invalid" : "is-valid") : "" ?>" 
                                       placeholder="contraseña" id="password1" name="password1" 
                                       value="<?= $password1 ?? '' ?>">
                                <div class="invalid-feedback">
                                    <br><?= RETRO_PASS_FORMATO ?>
                                </div>
                            </div>
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control <?= isset($errorPasswordNoRepetido) ? ($errorPasswordNoRepetido ? "is-invalid" : "is-valid") : "" ?>"  
                                       placeholder="Repita la contraseña" id="password2" name="password2" value="<?= $password2 ?? '' ?>">
                                <div class="invalid-feedback">
                                    <?= RETRO_PASS_REPETIDO ?>
                                </div>
                            </div>
                            <div class="input-group my-2">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control <?= isset($errorEmailFormato) ? ($errorEmailFormato ? "is-invalid" : "is-valid") : "" ?>"
                                       placeholder="e-Mail" name="email" id="email" value="<?= $email ?? '' ?>">
                                <div class="invalid-feedback">
                                    <?= RETRO_EMAIL_FORMATO ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <input type="submit" value="Registrar" class="btn btn-info" name="enviar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity = "sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin = "anonymous"></script>
    </body>
</html>