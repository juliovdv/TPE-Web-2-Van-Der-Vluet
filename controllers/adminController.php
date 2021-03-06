<?php
include_once('models/generosModel.php');
include_once('models/reseniasModel.php');
include_once('views/reseniasView.php');
include_once('helpers/auth.helper.php');
include_once('models/usuariosModel.php');




class adminController
{
    private $modelGeneros;
    private $modelResenias;
    private $view;

    public function __construct()
    {
        $this->modelUsuarios = new usuariosModel();
        $this->modelGeneros = new generosModel();
        $this->modelResenias = new reseniasModel();
        $this->view = new reseniasView();
    }
    //Funciones de adminiostrador RESEÑAS

    //**Llama a la funcion vista Administrador */
    public function admin()
    {
        $this->mensajeNoAdmin();
        $tablaGeneros = $this->modelGeneros->traerGeneros();
        $tablaResenias = $this->modelResenias->traerResenias();
        $this->view->vistaAdmin($tablaResenias, $tablaGeneros);
    }
    //Tabla Resenias

    //**Toma los valores por POST y llama a la funcion para agregar una reseña a la base de datos */
    public function agregarResenia()
    {
        $this->mensajeNoAdmin();
        $nombrepelicula = $_POST['nombre_pelicula'];
        $usuario =  $_SESSION["USUARIO"];
        $resenia = $_POST['resenia'];
        $genero = $_POST['genero'];
        if (empty($nombrepelicula) || empty($usuario) || empty($resenia) || empty($resenia))
            $this->view->mensajeError("Complete todos los campos");
        else {
            if ($_FILES['input_name']['type'] == "image/jpg" || $_FILES['input_name']['type'] == "image/jpeg" || $_FILES['input_name']['type'] == "image/png")
                $success = $this->modelResenias->guardarResenia($nombrepelicula, $usuario, $resenia, $genero, $_FILES['input_name']['tmp_name']);
            else
                $success = $this->modelResenias->guardarResenia($nombrepelicula, $usuario, $resenia, $genero);
        }

        if ($success)
            header('Location: ' . BASE_URL . "admin");
        else
            $this->view->mensajeError("El agregado no ha se completo correctamente");
    }
    //**Toma el id de una reseña y llama a la funcion para eliminarla de la base de datos */
    public function eliminarResenia($id)
    {
        $this->mensajeNoAdmin();
        $this->modelResenias->eliminarReseniaDB($id);
        header('Location: ' . BASE_URL . "admin");
    }
    //**Llama a la vista Editar reseña */
    public function modificarResenia($id)
    {
        $this->mensajeNoAdmin();
        $resenia = $this->modelResenias->traerResenia($id);
        $tablagenero = $this->modelGeneros->traerGeneros();
        $this->view->vistaEditarResenia($id, $resenia, $tablagenero);
    }
    //**Toma los datos por POST y llama a la funcion para modificarlos en la base de datos segun el ID  */
    public function editarResenia()
    {
        $this->mensajeNoAdmin();
        $nombrepelicula = $_POST['nombre_pelicula'];
        $usuario =  $_SESSION["USUARIO"];
        $resenia = $_POST['resenia'];
        $genero = $_POST['genero'];
        $id = $_POST['id_resenia'];
        if (!empty($nombrepelicula) && !empty($usuario) && !empty($resenia) && !empty($resenia)) {
            $this->modelResenias->editarReseniaDB($id, $nombrepelicula, $usuario, $resenia, $genero);
            header('Location: ' . BASE_URL . "admin");
        } else
            $this->view->mensajeError("Complete todos los campos");
    }

    //Imagenes
    public function borrarImagen($id)
    {
        $this->mensajeNoAdmin();
        $this->modelResenias->borrarImagenDB($id);
        header('Location: ' . BASE_URL . "detalle/" . $id);
    }

    //Tabla genero

    //** Agrega un genero */
    public function agregarGenero()
    {
        $this->mensajeNoAdmin();
        $genero = $_POST['genero'];
        if (empty($genero))
            $this->view->mensajeError("Complete todos los campos");
        else
            $success = $this->modelGeneros->guardarGenero($genero);
        if ($success)
            header('Location: ' . BASE_URL . "admin");
        else
            $this->view->mensajeError("El agregado no ha se completo correctamente ");
    }
    //** Eliminar genero por id */
    public function eliminarGenero($id)
    {
        $this->mensajeNoAdmin();
        $success = $this->modelGeneros->eliminarGeneroDB($id);
        if ($success)
            header('Location: ' . BASE_URL . "admin");
        else
            $this->view->mensajeError("El eliminado no ha se completo correctamente, es posible que alla reseñas vinculadas a este genero. ");
    }

    //** Llama a la vista editar genero */
    public function modificarGenero($id)
    {
        $this->mensajeNoAdmin();
        $tablagenero = $this->modelGeneros->traerGeneros();
        $this->view->vistaEditarGenero($id, $tablagenero);
    }
    //** Toma parametros por POST y llama a la funcion para editar un genero */
    public function editarGenero()
    {
        $this->mensajeNoAdmin();
        $id = $_POST['id_genero'];
        $genero = $_POST['genero'];
        if (!empty($genero)) {
            $this->modelGeneros->editarGeneroDB($id, $genero);
            header('Location: ' . BASE_URL . "admin");
        } else
            $this->view->mensajeError("Complete todos los campos");
    }
    //Funciones de adminiostrador USUARIOS
    /**
     * Pide al modelo la tabla usuarios y la pasa a la vista
     */
    public function adminUsuarios()
    {
        $this->mensajeNoAdmin();
        $tablausuarios = $this->modelUsuarios->traerUsuarios();
        $this->view->vistaUsuarios($tablausuarios);
    }
    /**
     * Recibe id y admin, y pasa al modelo para que se modifique el valor admin de ese id
     */
    public function hacerAdmin($id, $admin)
    {
        $this->mensajeNoAdmin();
        if ($admin == '0' || $admin == '1') {
            $this->modelUsuarios->hacerAdminDB($id, $admin);
            header('Location: ' . BASE_URL . "usuarios");
        }
    }
    /**
     * REcibe id y pasa al modelo ese id para que ese usuario sea borrado
     */
    public function borrarUsuario($id)
    {
        $this->mensajeNoAdmin();
        $this->modelUsuarios->borrarUsuarioDB($id);
        header('Location: ' . BASE_URL . "usuarios");
    }
    /**
     * Bloquea el acceso a las pesonas que no sean admin
     */
    public function mensajeNoAdmin()
    {
        if (!AuthHelper::esAdmin()) {
            $this->view->mensajeErrorPermisos("Esta opcion requiere permisos de administrador");
            die;
        }
    }
}
