<?php
// Incluye el archivo PHP que contiene la función 'analizador'
include 'tablapredic.php';

$resultado = "";
// Verifica si el formulario ha sido enviado y si la entrada no está vacía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["entrada"])) {
        $entrada = $_POST["entrada"];
        $resultado = analizador($entrada); // Procesa la entrada
    } else {
        // Si no hay entrada, puedes establecer un mensaje predeterminado o dejarlo en blanco
        $resultado = "Por favor ingrese una entrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla Predictiva</title>
</head>
<body>
    <h2>Tabla Predictiva</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="entrada">Entrada:</label><br>
        <input type="text" id="entrada" name="entrada" size="100"><br><br>
        <input type="submit" value="PROCESAR">
    </form>

    <!-- Área para mostrar los resultados -->
    <h3>Resultado:</h3>
    <textarea rows="15" cols="150" readonly><?php echo htmlspecialchars($resultado); ?></textarea>
</body>
</html>


