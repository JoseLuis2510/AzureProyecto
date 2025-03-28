<?php
// Conexión a la base de datos
include('ConexionBD.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $idMaterial = $_POST['idMaterial'];
    $nombreMaterial = $_POST['nombreMaterial'];
    $precioMaterial = $_POST['precioMaterial'];
    $stockMaterial = $_POST['stockMaterial'];
    $proveedorMaterial = $_POST['proveedorMaterial'];
    $usuario = $_SESSION['usuario']; 

    
    $sqlSelect = "SELECT * FROM inventario WHERE id_material = '$idMaterial'";
    $result = $conn->query($sqlSelect);
    $material = $result->fetch_assoc();

    
    $nombreMaterialAnterior = $material['nombre_material'];
    $precioMaterialAnterior = $material['precio'];
    $stockMaterialAnterior = $material['stock'];
    $proveedorMaterialAnterior = $material['proveedor'];

    
    $sqlUpdate = "UPDATE inventario SET nombre_material = '$nombreMaterial', precio = '$precioMaterial', stock = '$stockMaterial', proveedor = '$proveedorMaterial' 
                  WHERE id_material = '$idMaterial'";

    if ($conn->query($sqlUpdate) === TRUE) {
        
        $sqlAuditoria = "INSERT INTO auditoria (id_material, accion, nombre_material_anterior, precio_anterior, stock_anterior, proveedor_anterior, 
                                          nombre_material_nuevo, precio_nuevo, stock_nuevo, proveedor_nuevo) 
                         VALUES ('$idMaterial', 'editar', '$nombreMaterialAnterior', '$precioMaterialAnterior', '$stockMaterialAnterior', '$proveedorMaterialAnterior', 
                                 '$nombreMaterial', '$precioMaterial', '$stockMaterial', '$proveedorMaterial')";
        $conn->query($sqlAuditoria);

        
        header("Location: Inventario.php");
    } else {
        echo "Error al actualizar el material: " . $conn->error;
    }
}
?>
