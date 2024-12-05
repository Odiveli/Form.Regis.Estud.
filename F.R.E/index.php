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
        ($age < 18 || $age > 99) ;
        echo "<script>Swal.fire('Error', 'La edad debe estar entre 18 y 99', 'error');</script>";
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
}

// Función para limpiar los datos recibidos
function test_input($data) {
    $data = trim($data);  // Elimina espacios extra al principio y final
    $data = stripslashes($data);  // Elimina barras invertidas
    $data = htmlspecialchars($data);  // Convierte caracteres especiales en entidades HTML
    return $data;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro de Estudiantes</title>
</head>
<body>

    <h2>Registro de Estudiantes</h2><br>
    <form method="POST" action="procesar.php">

        <label for="name">Nombre Completo:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">

        <label for="age">Edad:</label><br>
        <input type="number" id="age" name="age" min="18" max="99" value="<?php echo $age; ?>"required>
        
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required><br><br>

        <div class="form-group">
                <label for="gender">Género:</label>
                <select id="gender" name="gender" required>
                    <option value="Masculino"<?php if ($gender == 'Masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="Femenino"<?php if ($gender == 'Femenino') echo 'selected'; ?>>Femenino</option>
                    <option value="Otro"<?php if ($gender == 'Otro') echo 'selected'; ?>>Otro</option>
                </select><br><br>
            
        </div>
       
        <label for="area_interes">Área de interés:</label>
        <select id="area_interes" name="area_interes" onchange="showCourses()" required>
            <option value="">Seleccione una área</option>
            <option value="Tecnología">Tecnología</option>
            <option value="Gastronomía">Gastronomía</option>
            <option value="Administración">Administración</option>
        </select><br><br>

        <label for="curso_interes">Curso de interés:</label>
        <select id="curso_interes" name="curso_interes" required>
            <option value="">Seleccione un curso</option>
        </select><br><br>

 
        <button type="submit" >Registrar</button>
        <button type="reset" class="btn btn-custom">Borrar</button>

</div>

<script>
    function showCourses() {
        const area = document.getElementById('area_interes').value;
        const cursoSelect = document.getElementById('curso_interes');
        cursoSelect.innerHTML = '';

        const cursos = {
            'Tecnología': ['Programación de Sofware', 'Diseño Web y Aplicativos Moviles', 'Base de Datos'],
            'Gastronomía': ['Reposteria', 'Cocina Gourmet', 'Cocina Saludable'],
            'Administración': ['Contable', 'Mercadeo', 'Producción']
        };

        cursos[area].forEach(curso => {
            const option = document.createElement('option');
            option.value = curso;
            option.textContent = curso;
            cursoSelect.appendChild(option);
        });
    }

    function showData() {
        Swal.fire({
            title: 'Lista de estudiantes',
            html: document.querySelector('table').outerHTML,
            icon: 'info',
            showConfirmButton: true,
            confirmButtonText: 'Cerrar',
            width: '20%',
            background: '#f4f4f4',
            customClass: {
                popup: 'my-custom-popup',
                title: 'my-custom-title',
                htmlContainer: 'my-custom-content'
            }
        });
    }

    function editRecord(id) {
        window.location.href = 'edit.php?id=' + id;
    }

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?id=' + id + '&redirect=index.php';
            }
        });
    }
</script>
        
    </form>
    <style>
        .btn-custom {
            border-radius: 50px; /* Bordes redondeados */
            background-color: #007bff; /* Color azul */
            color: white; /* Color de la letra */
            border: none; /* Sin borde */
            padding: 10px 20px; /* Espaciado interno */
            transition: background-color 0.3s; /* Transición suave */
        }

        .btn-custom:hover {
            background-color: #0056b3; /* Azul más oscuro al pasar el ratón */
            text-decoration: none; /* Sin subrayado */
        }

        .btn-custom:focus {
            box-shadow: none; /* Elimina el efecto de foco */
        }

        .form-container { width: 10px; margin: auto; padding: 100px; border: 5px solid #ccc; border-radius: 10px; }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 80px;
            padding: 80px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        h2 {
            margin-top: -120px;
            text-align-last: 20px;   /* Centra el texto */
            font-size: 50px;       /* Cambia el tamaño de la fuente */
            color: blue;        /* Cambia el color del texto a azul */

        }
        .form-container {
            max-width: 80px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 80%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="radio"], input[type="checkbox"] {
            width: auto;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-group {
            margin-bottom: 30px;
        }
    </style>

    <?php
    // Mostrar los datos si el formulario ha sido enviado y es válido
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $nameErr == "" && $ageErr == "" && $emailErr == "" && $genderErr == "" && $courseErr == "" && $interestsErr == "") {
        echo "<h3>Información recibida:</h3>";
        echo "Nombre: " . $name . "<br>";
        echo "Edad: " . $age . "<br>";
        echo "Correo electrónico: " . $email . "<br>";
        echo "Género: " . $gender . "<br>";
        echo "Curso seleccionado: " . $course . "<br>";        
        echo "Áreas de interés: " . implode(", ", $interests) . "<br>";
    }
    ?>

</body>
</html>