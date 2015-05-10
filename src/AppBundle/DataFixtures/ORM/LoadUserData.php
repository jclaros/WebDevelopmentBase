<?php
namespace AppBundle\DataFixtures\ORM;
/**
 * Description of LoadBaseUsers
 *
 * @author Jonathan Claros
 */
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
      $admin = $manipulator->create("admin", "admin", "admin@main.com", true, true);
      $user = $manipulator->create("user", "user", "user@main.com", true, false);
      
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
