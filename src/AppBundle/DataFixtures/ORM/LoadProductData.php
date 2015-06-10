<?php
namespace AppBundle\DataFixtures\ORM;
/**
 * Description of LoadProductData
 * Basic Load product class, which is used in order to populate data on dev environments or seed information on
 * first production deploy.
 *
 * @author Jonathan Claros
 */
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Product;

class LoadProductData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface {
  
  /**
   * @var ContainerInterface
   */
  private $container;
  
  
  public function load(ObjectManager $manager) {
      
    for($i = 1; $i < 2001; $i++){
      $newobj = new Product();
      $newobj->setTitle("Brand new bmw x" . $i);
      $newobj->setPrice(50000 + ($i *20));
      $manager->persist($newobj);
      if(($i % 200) == 0){
        $manager->flush();
      }
    }
  }

  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  /**
   * this sets the appropriate order for the fixture to be loaded
   */
  public function getOrder()
  {
      return 2; 
  }
  
}