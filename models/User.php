<?php
// models/User.php

require_once __DIR__ . '/../config/database.php';

class User {
    
    public static function getByEmail($email) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Obtener usuario por ID
    public static function getById($id_usuario) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    // Comprobar si el correo existe en OTRO usuario
    public static function getByEmailExceptUser($email, $id_usuario) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM usuarios WHERE correo = :correo AND id_usuario != :id_usuario LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public static function create($nombre, $correo, $password) {
        $db = Database::getInstance();
        $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    // Actualizar datos del usuario
    public static function update($id_usuario, $nombre, $correo, $password = null) {
        $db = Database::getInstance();
        
        if ($password) {
            $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo, password = :password WHERE id_usuario = :id_usuario";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE usuarios SET nombre = :nombre, correo = :correo WHERE id_usuario = :id_usuario";
            $stmt = $db->prepare($sql);
        }
        
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    // Eliminar registro del usuario
    public static function delete($id_usuario) {
        $db = Database::getInstance();
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>