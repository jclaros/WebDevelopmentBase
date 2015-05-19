<?php
/**
 * Created by IntelliJ IDEA.
 * User: amed
 * Date: 5/18/15
 * Time: 8:41 PM
 */
namespace AppBundle\Services;

use AppBundle\Entity\MenuOpciones;

class MenuOpcionesService {

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }


    public function getAllMenuOptions(){

        try {
            //$menuOps = $this->em->getRepository('AppBundle:Perfiles')->find(array('idPerfil' => 1)); // Podemos obtener datos de cualquier entidad mediante sus atributos de clase que hayan sido mapeados a la BD
            $menuOps = $this->em->getRepository('AppBundle:MenuOpciones')->findAll();
        } catch (Exception $exc) {
            throw new Exception("Error almacenando la información");
        }

        return $menuOps;
    }


}