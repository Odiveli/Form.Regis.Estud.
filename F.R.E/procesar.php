<?php
$servername = "localhost";
$username = "root"; // Ajusta según tu configuración
$password = ""; // Ajusta según tu configuración
$dbname = "f.r.e.";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Definir variables y establecer valores vacíos
$name = $age = $email = $course = $gender = "";
$interests = [];
$nameErr = $ageErr = $emailErr = $courseErr = $genderErr = $interestsErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar nombre completo
    if (empty($_POST["name"])) {
        $nameErr = "El nombre es obligatorio";
    } else {
        $name = test_input($_POST["name"]);
    }

    // Validar edad
    if (empty($_POST["age"])) {
        $ageErr = "La edad es obligatoria";
    } else {
        $age = test_input($_POST["age"]);
        if ($age < 18 || $age > 99) {
            $ageErr = "La edad debe estar entre 18 y 99";
        }
    }

    // Validar correo electrónico
    if (empty($_POST["email"])) {
        $emailErr = "El correo electrónico es obligatorio";
    } else {
        $email = test_input($_POST["email"]);
        // Verificar formato de correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Formato de correo electrónico inválido";
        }
    }

    // Validar género
    if (empty($_POST["gender"])) {
        $genderErr = "Debes seleccionar un género";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Validar curso seleccionado
    if (empty($_POST["course"])) {
        $courseErr = "Debes seleccionar un curso";
    } else {
        $course = test_input($_POST["course"]);
    } 

    // Validar áreas de interés
    if (empty($_POST["interests"])) {
        $interestsErr = "Debes seleccionar al menos un área de interés";
    } else {
        $interests = $_POST["interests"];
    }
    // Si no hay errores, insertar los datos en la base de datos
    if (empty($nameErr) && empty($ageErr) && empty($emailErr) && empty($genderErr) && empty($courseErr)) {
        $sql = "INSERT INTO estudiantes (name, age, email, gender, course) VALUES ('$name', '$age', '$email', '$gender', '$course')";

    
        // Mostrar los datos si no hay errores
        if ($conn->query(query: $sql) === TRUE) { 
            echo "<h2>Datos recibidos y registrados:</h2>";
            echo "<tableborder='1' cellpadding='10'>";
            echo "<tr><th>Campo</th><th>Valor</th></tr>";
            echo "<tr><td><strong>Nombre:</strong></td><td>" . $name . "</td></tr>";
            echo "<tr><td><strong>Edad:</strong></td><td>" . $age . "</td></tr>";
            echo "<tr><td><strong>Correo electrónico:</strong></td><td>" . $email . "</td></tr>";
            echo "<tr><td><strong>Género:</strong></td><td>" . $gender . "</td></tr>";
            echo "<tr><td><strong>Curso seleccionado:</strong></td><td>" . $course . "</td></tr>";        
            echo "</table>";
        } else {
            echo "Error al registrar los datos: " . $conn->error;
        }
    } else {
            // Si hay errores, mostrar los mensajes de error
            echo "<h2>Errores en los datos:</h2>";
            echo "<ul>";
    if ($nameErr) echo "<li>" . $nameErr . "</li>";
    if ($ageErr) echo "<li>" . $ageErr . "</li>";
    if ($emailErr) echo "<li>" . $emailErr . "</li>";
    if ($genderErr) echo "<li>" . $genderErr . "</li>";
    if ($courseErr) echo "<li>" . $courseErr . "</li>";        
    if ($interestsErr) echo "<li>" . $interestsErr . "</li>";
    echo "</ul>";
}


}

// Función para limpiar los datos recibidos
function test_input($data) {
    $data = trim($data);  // Elimina espacios extra al principio y final
    $data = stripslashes($data);  // Elimina barras invertidas
    $data = htmlspecialchars($data);  // Convierte caracteres especiales en entidades HTML
    return $data;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Formulario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: black;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: black;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f8;
            color: #4CAF50;
        }

        tr:hover {
            background-color: black;
        }

        .result {
            margin: 20px 0;
            background-color: black;
            padding: 10px;
            border-radius: 5px;
        }

        .result strong {
            color: black;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .link-back {
            text-align: center;
            margin-top: 20px;
        }

        .link-back a {
            color: blueviolet;
            text-decoration: none;
            font-weight: bold;
        }

        .link-back a:hover {
            text-decoration-color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Resultado del Formulario</h1>
    <div>
        <?php
        // Mostrar los datos procesados aquí
        if (empty($nameErr) && empty($ageErr) && empty($emailErr) && empty($genderErr) && empty($courseErr) && empty($interestsErr)) {
            echo "<h2>Datos Recibidos:</h2>";
            echo "<table>";
            echo "<tr><th>Campo</th><th>Valor</th></tr>";
            echo "<tr><td><strong>Nombre:</strong></td><td>" . $name . "</td></tr>";
            echo "<tr><td><strong>Edad:</strong></td><td>" . $age . "</td></tr>";
            echo "<tr><td><strong>Correo Electrónico:</strong></td><td>" . $email . "</td></tr>";
            echo "<tr><td><strong>Género:</strong></td><td>" . $gender . "</td></tr>";
            echo "<tr><td><strong>Curso Seleccionado:</strong></td><td>" . $course . "</td></tr>";
            echo "<tr><td><strong>Áreas de Interés:</strong></td><td>" . implode(", ", $interests) . "</td></tr>";
            echo "</table>";
        } else 
        {
            // Si hay errores, mostrar los mensajes de error
            echo "<h2 class='error'>Errores en los datos:</h2>";
            echo "<ul class='error'>";
            if ($nameErr) echo "<li>" . $nameErr . "</li>";
            if ($ageErr) echo "<li>" . $ageErr . "</li>";
            if ($emailErr) echo "<li>" . $emailErr . "</li>";
            if ($genderErr) echo "<li>" . $genderErr . "</li>";
            if ($courseErr) echo "<li>" . $courseErr . "</li>";
            if ($interestsErr) echo "<li>" . $interestsErr . "</li>";
            echo "</ul>";
        }
        ?>
    </div>
    <div class="link-back">
        <p><a href="index.php">Volver al formulario</a></p>
    </div>
</div>

</body>
</html>