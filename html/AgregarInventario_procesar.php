<?php

include('ConexionBD.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreMaterial = $_POST['nombreMaterial'];
    $precioMaterial = $_POST['precioMaterial'];
    $stockMaterial = $_POST['stockMaterial'];
    $proveedorMaterial = $_POST['proveedorMaterial'];
    $usuario = $_SESSION['usuario']; 

    
    $sqlCheck = "SELECT id_material, stock FROM inventario 
                 WHERE nombre_material = '$nombreMaterial' 
                 AND precio = '$precioMaterial' 
                 AND proveedor = '$proveedorMaterial'";

    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $id_material = $row['id_material'];
        $nuevoStock = $row['stock'] + $stockMaterial; 

        
        $sqlUpdate = "UPDATE inventario 
                      SET stock = '$nuevoStock' 
                      WHERE id_material = '$id_material'";

        if ($conn->query($sqlUpdate) === TRUE) {
            
            $sqlAuditoria = "INSERT INTO auditoria (id_material, accion, nombre_material_nuevo, precio_nuevo, stock_nuevo, proveedor_nuevo) 
                             VALUES ('$id_material', 'actualizar', '$nombreMaterial', '$precioMaterial', '$nuevoStock', '$proveedorMaterial')";
            $conn->query($sqlAuditoria);

            
            header("Location: Inventario.php");
        } else {
            echo "Error al actualizar el stock: " . $conn->error;
        }
    } else {
        
        $sqlInsert = "INSERT INTO inventario (nombre_material, precio, stock, proveedor) 
                      VALUES ('$nombreMaterial', '$precioMaterial', '$stockMaterial', '$proveedorMaterial')";

        if ($conn->query($sqlInsert) === TRUE) {
            
            $id_material = $conn->insert_id;

            
            $sqlAuditoria = "INSERT INTO auditoria (id_material, accion, nombre_material_nuevo, precio_nuevo, stock_nuevo, proveedor_nuevo) 
                             VALUES ('$id_material', 'agregar', '$nombreMaterial', '$precioMaterial', '$stockMaterial', '$proveedorMaterial')";
            $conn->query($sqlAuditoria);

            
            header("Location: Inventario.php");
        } else {
            echo "Error al agregar el material: " . $conn->error;
        }
    }
}
?>
