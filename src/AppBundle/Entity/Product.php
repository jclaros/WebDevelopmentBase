<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Movie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProductRepository")
 * 
 * @Serializer\XmlRoot("product")
 *
 * @Hateoas\Relation(
 *    "self", 
 *    href = @Hateoas\Route(
 *      "get_product",
 *      parameters = { "id" = "expr(object.getId())"  }
 *    )
 * )
 * @Hateoas\Relation(
 *    "delete", 
 *    href = @Hateoas\Route(
 *      "delete_product",
 *      parameters = { "id" = "expr(object.getId())"  }
 *    )
 * )
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="decimal")
     */
    
    protected $price;
    
    /**
     * @var string
     * @ORM\Column(type="datetime")
     */
    
    protected $createdAt;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @return String
     */
    public function getTitle(){
      return $this->title;
    }
    
    /**
     * 
     * @param String $title
     */
    public function setTitle($title){
      $this->title = $title;
    }
    
    /**
     * 
     * @return decimal
     */
    public function getPrice(){
      return $this->price;
    }
    
    /**
     * 
     * @param decimal $price
     */
    public function setPrice($price){
      $this->price = $price;
    }
    
    /**
     * 
     * @return date
     */
    public function getBorrowed(){
      return $this->createdAt;
    }
    
    public function __construct() {
      $this->createdAt = new \DateTime();
    }
    
}
