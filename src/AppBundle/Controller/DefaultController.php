<?php

namespace AppBundle\Controller;

use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends FOSRestController
{
    /**
     * @Rest\Get(name="_list", defaults={"_format" = "json"})
     * @REST\View()
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets list of products",
     *   output = "Array",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     */
    public function getProductsAction(Request $request)
    {
//      if(!$this->isGranted("ROLE_ADMIN")){
//        
//      }
      //$this->denyAccessUnlessGranted('ROLE_ADMIN');
      $limit = $request->query->getInt('limit', 10);
      $page = $request->query->getInt('page', 1);
      $sorting = $request->query->get('sorting', array());
      if(empty($sorting)){
        $sorting["id"] = "asc";
      }
      $productsPager = $this->getDoctrine()->getManager()
          ->getRepository('AppBundle:Product')
          ->findAllPaginated($limit, $page, $sorting);

      $pagerFactory = new PagerfantaFactory();

      return $pagerFactory->createRepresentation(
          $productsPager,
          new Route('get_products_list', array(
              'limit' => $limit,
              'page' => $page,
              'sorting' => $sorting
          ))
      );
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Create Movie",
     *   output = "Array",
     *   authentication = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     * 
     */
    public function postProductsAction(Request $request)
    {
//      if(!$this->isGranted("ROLE_ADMIN")){
//        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
//      }
      $content = json_decode($request->getContent());
      if(empty($content)){
        return new \Symfony\Component\HttpFoundation\Response("error with the data", 401);
      }
      try {
          
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("error with the data", 401);
      }
      
      
      return $this->handleView($this->view($movie))->setStatusCode(201);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get one movie",
     *   output = "Array",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     * 
     */
    public function getProductAction($id)
    { 
      try {
        
        $movie = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
        return $this->handleView($this->view($movie));  
          
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("resource not found", 404);
      }
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "remove one movie",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     * 
     */
    public function deleteProductAction($id)
    { 
      try {
        
          
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("resource not found", 404);
      }
    }
    
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Put movie",
     *   output = "Array",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     * 
     */
    public function putProductAction($id)
    { 
      
      if(!$this->isGranted("ROLE_ADMIN")){
        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
      }
      
      try {
        
        $movie = $this->getDoctrine()->getRepository("AppBundle:Movie")->find($id);
        if(!($movie instanceof \AppBundle\Entity\Movie)){
          return new \Symfony\Component\HttpFoundation\Response("Resource not found", 404);
        }
        
        try {
            $em = $this->getDoctrine()->getManager();
            
            $content = json_decode($this->getRequest()->getContent());
            $movie->setTitle($content->title);
            $movie->setYear($content->year);
            $movie->setBorrowed($content->borrowed);
            
            $em->persist($movie);
            $em->flush();
            
        } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("something got broken", 401);
        }  
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("something got broken", 401);
      }
      
      return $this->handleView($this->view($movie)); 
    }
    
}
