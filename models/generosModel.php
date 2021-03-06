<?php
class generosModel 
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost' . ';dbname=db_resenias;charset=utf8', 'root', '');
        $host = 'localhost';
        $userName = 'root';
        $password = '';
        $database = 'db_resenias';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $userName, $password);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    //**Trae todos las tuplas de la tabla genero */
    public function traerGeneros()
    {
        $sentencia = $this->db->prepare("SELECT * FROM genero");
        $sentencia->execute(array());
        $detalletabla = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $detalletabla;
    }

    //**Trae genero segun id */
    public function traerGenerosporID($id){
        $sentencia = $this->db->prepare("SELECT * FROM genero WHERE id_genero = ?");
        $sentencia->execute(array(($id)));   
        $detalle = $sentencia->fetch(PDO::FETCH_OBJ);
        return $detalle;
    }
    //**Inserta un nuevo genero */
    public function guardarGenero($genero){
        $sentencia = $this->db->prepare('INSERT INTO genero (nombre) VALUES (?)');
        return $sentencia->execute([$genero]);
    }
    //**Edita un genero segun id */
    public function editarGeneroDB($id, $genero){
        $sentencia = $this->db->prepare('UPDATE genero SET nombre = ? WHERE id_genero = ?');
        $sentencia->execute([$genero, $id]);
    }
    //**Elimina un genero segun id */
    public function eliminarGeneroDB($id){
        $sentencia = $this->db->prepare('DELETE FROM genero WHERE id_genero = ?');
        return $sentencia->execute([$id]);
        
    }
}
?>