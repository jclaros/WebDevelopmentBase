<?php
namespace AppBundle\Services;
/**
 * Description of MovieService
 * Custom service to handle movies construction
 * @author Jonathan Claros <jclaros at lysoftbo.com>
 */
use AppBundle\Entity\Movie;

class MovieService {
  
  protected $em;
  
  public function __construct(\Doctrine\ORM\EntityManager $em) {
    $this->em = $em;
  }
  
  
  public function saveMovie($content){
    
    try {
          $movie = new \AppBundle\Entity\Movie();
          $movie->setBorrowed(false);
          $movie->setTitle($content->title);
          $movie->setYear($content->year);
          $this->em->persist($movie);
          $this->em->flush();
      } catch (Exception $exc) {
          throw new Exception("Error almacenando la información");
      }
      
      return $movie;
  }
  
  
}
