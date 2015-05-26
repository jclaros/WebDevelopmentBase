<?php
namespace AppBundle\DataFixtures\ORM;
/**
 * Description of LoadBaseUsers
 *
 * @author Jonathan Claros
 */
use AppBundle\Entity\MenuOpciones;
use AppBundle\Entity\Perfiles;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface {
  
  /**
   * @var ContainerInterface
   */
  private $container;
  
  
  public function load(ObjectManager $manager) {
      $manipulator = $this->container->get('fos_user.util.user_manipulator');

      // perfiles
      $perfil1 = new Perfiles();
      $perfil1->setNombre("Administracion");
      $perfil1->setDescripcion("Administrador del sistema");
      $perfil1->setEstado("Activo");

      $perfil2 = new Perfiles();
      $perfil2->setNombre("Consultas");
      $perfil2->setDescripcion("Consultas del sistema");
      $perfil2->setEstado("Activo");

      $manager->persist($perfil1);
      $manager->persist($perfil2);

      $coleccion1 = new ArrayCollection([$perfil1, $perfil2]);
      $coleccion2 = new ArrayCollection([$perfil1]);
      $coleccion3 = new ArrayCollection([$perfil2]);


      $opc1 = new MenuOpciones();
      $opc1->setOpcion("Administracion");
      $opc1->setTooltip("Modulo de administración del sistema");
      $opc1->setEstado("Activo");
      $opc1->setControlador("/admin");
      $opc1->setIdPerfil($coleccion2);

      $opc2 = new MenuOpciones();
      $opc2->setOpcion("Administracion de Usuarios");
      $opc2->setTooltip("Modulo de administración de usuarios del sistema");
      $opc2->setEstado("Activo");
      $opc2->setIdPadre($opc1);
      $opc2->setControlador("/admin/usuarios");
      $opc2->setIdPerfil($coleccion2);

      $opc3 = new MenuOpciones();
      $opc3->setOpcion("Administracion de Perfiles");
      $opc3->setTooltip("Modulo de administración de perfiles del sistema");
      $opc3->setEstado("Activo");
      $opc3->setIdPadre($opc1);
      $opc3->setControlador("/admin/perfiles");
      $opc3->setIdPerfil($coleccion2);

      $opc4 = new MenuOpciones();
      $opc4->setOpcion("Ayuda");
      $opc4->setTooltip("Modulo de ayuda");
      $opc4->setEstado("Activo");
      $opc4->setControlador("/ayuda");
      $opc4->setIdPerfil($coleccion1);

      $opc5 = new MenuOpciones();
      $opc5->setOpcion("Manual de usuario");
      $opc5->setTooltip("Manual de usuario web");
      $opc5->setEstado("Activo");
      $opc5->setIdPadre($opc4);
      $opc5->setControlador("/ayuda/manual");
      $opc5->setIdPerfil($coleccion1);

      $opc6 = new MenuOpciones();
      $opc6->setOpcion("Descargar manual");
      $opc6->setTooltip("Descargar manual");
      $opc6->setEstado("Activo");
      $opc6->setIdPadre($opc4);
      $opc6->setControlador("/ayuda/download");
      $opc6->setIdPerfil($coleccion1);


      $manager->persist($opc1);
      $manager->persist($opc2);
      $manager->persist($opc3);
      $manager->persist($opc4);
      $manager->persist($opc5);
      $manager->persist($opc6);

      $manager->flush();

      /**
       * @var $admin User
       */
      $admin = $manipulator->create("admin", "admin", "admin@main.com", true, true);
      $demo = $manipulator->create("demo", "demo", "demo@main.com", true, false);

      $admin->setPerfil($perfil1);
      $demo->setPerfil($perfil2);

      $manager->persist($admin);
      $manager->persist($demo);

      $manager->flush();

  }

  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  /**
   * {@inheritDoc}
   */
  public function getOrder()
  {
      return 1; 
  }
  
}
