<?php
// Conexión a la base de datos
include('ConexionBD.php');


if (isset($_GET['id'])) {
    $idMaterial = $_GET['id'];
    $usuario = $_SESSION['usuario']; 

    
    $sqlSelect = "SELECT * FROM inventario WHERE id_material = '$idMaterial'";
    $result = $conn->query($sqlSelect);
    $material = $result->fetch_assoc();

    
    $nombreMaterial = $material['nombre_material'];
    $precioMaterial = $material['precio'];
    $stockMaterial = $material['stock'];
    $proveedorMaterial = $material['proveedor'];

    
    $sqlDelete = "DELETE FROM inventario WHERE id_material = '$idMaterial'";

    if ($conn->query($sqlDelete) === TRUE) {
        $sqlAuditoria = "INSERT INTO auditoria (id_material, accion, nombre_material_anterior, precio_anterior, stock_anterior, proveedor_anterior) 
                         VALUES ('$idMaterial', 'eliminar', '$nombreMaterial', '$precioMaterial', '$stockMaterial', '$proveedorMaterial')";
        $conn->query($sqlAuditoria);

        
        header("Location: Inventario.php");
    } else {
        echo "Error al eliminar el material: " . $conn->error;
    }
}
?>
