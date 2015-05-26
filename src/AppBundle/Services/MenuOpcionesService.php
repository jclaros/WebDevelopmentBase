<?php
/**
 * Created by IntelliJ IDEA.
 * User: amed
 * Date: 5/18/15
 * Time: 8:41 PM
 */
namespace AppBundle\Services;

use AppBundle\Entity\MenuOpciones;
use AppBundle\Entity\User;

class MenuOpcionesService {

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }


    public function getAllMenuOptions(User $user){

        try {
            $menuOps = $user->getPerfil();
        } catch (Exception $exc) {
            throw new Exception("Error almacenando la información");
        }

        return $menuOps;
    }


}