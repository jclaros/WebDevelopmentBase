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
     * @Rest\Get(name="_list", defaults={"_format" = "json"})
     * @REST\View()
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets list of Menu opciones",
     *   output = "Array",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     */
    public function getMenuOpcionesAction(Request $request)
    {

//        $limit = $request->query->getInt('limit', 10);
//        $page = $request->query->getInt('page', 1);
//        $sorting = $request->query->get('sorting', array());
        if(empty($sorting)){
            $sorting["id"] = "asc";
        }
        $serviceMenu = $this->get('appbundle.menu_service');
        $menusOpciones = $serviceMenu->getAllMenuOptions();

//        $productsPager = $this->getDoctrine()->getManager()
//            ->getRepository('AppBundle:Product')
//            ->findAllPaginated($limit, $page, $sorting);
//
//        $pagerFactory = new PagerfantaFactory();

        return $menusOpciones;
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Create Movie",
     *   output = "Array",
     *   authentication = false,
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
      $content = json_decode($request->getContent(), true);
      if(empty($content) || !isset($content["title"]) || !isset($content["price"])){
        return new \Symfony\Component\HttpFoundation\Response(json_encode(["error"=>"not enough parameters on the body, you need title and price"]), 401);
      }
      $product = new \AppBundle\Entity\Product();
      $product->setTitle($content["title"]);
      $product->setPrice($content["price"]);
      
      try {
          $em = $this->getDoctrine()->getManager();
          $em->persist($product);
          $em->flush();
          return $this->handleView($this->view($product))->setStatusCode(201);
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("resource not found", 404);
      }
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get one product",
     *   output = "Object",
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
        $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
        return $product;
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("resource not found", 404);
      }
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "remove one product",
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
      $product = $this->getDoctrine()->getManager()
          ->getRepository('AppBundle:Product')
          ->find($id);
      
      if(!$product){
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
      }
      
      $em = $this->getDoctrine()->getManager();
      
      try {
        $em->remove($product);
        $em->flush();  
        return new \Symfony\Component\HttpFoundation\Response(json_encode(["success"=>"product deleted"]), 200);
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("resource not found", 404);
      }
    }
    
    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Put product",
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
      
//      if(!$this->isGranted("ROLE_ADMIN")){
//        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
//      }
      
      try {
        
        $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
        if(!$product){
          throw $this->createNotFoundException(
              'No product found for id '.$id
          );
        }
        
        $em = $this->getDoctrine()->getManager();
        
        try {
            
            
            $content = json_decode($this->getRequest()->getContent(), true);
            if(isset($content["title"])){
              $product->setTitle($content["title"]);
            }
            if(isset($content["price"])){
              $product->setPrice($content["price"]);
            }
            
            $em->flush();
            
        } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("something got broken", 401);
        }  
      } catch (Exception $exc) {
          return new \Symfony\Component\HttpFoundation\Response("something got broken", 401);
      }
      
      return $product; 
    }
    
}
