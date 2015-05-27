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

class DefaultController extends FOSRestController {

  /**
   * @Rest\Get(name="_list", defaults={"_format" = "json"})
   * @REST\View()
   * @ApiDoc(
   *   resource = true,
   *   description = "Gets list of products, you can pass limit=>int, page=>int, sorting=>array, query=>string",
   *   output = "Array",
   *   authentication = false,
   *   statusCodes = {
   *     200 = "Returned when successful",
   *     404 = "Returned when the page is not found"
   *   }
   * )
   */
  public function getProductsAction(Request $request) {

      if(!$this->isGranted("ROLE_ADMIN")){
//        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
      }

    $limit = $request->query->getInt('limit', 10);
    $page = $request->query->getInt('page', 1);
    $sorting = $request->query->get('sorting', array());
    $query = $request->query->get('q', false);
    if(empty($sorting)){
      $sorting["id"] = "asc";
    }
    $productsPager = $this->getDoctrine()->getManager()
        ->getRepository('AppBundle:Product')
        ->findAllPaginated($limit, $page, $sorting, $query);

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
     * @Rest\Get(name="menu", defaults={"_format" = "json"})
     * @REST\View()
     * @ApiDoc(
     *   resource = true,
     *   description = "Example of function",
     *   output = "Array",
     *   authentication = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
     */
    public function getMenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $conection = $em->getConnection();
        $st = $conection->prepare("SELECT f_abm_menu_opciones(:p_opc::VARCHAR,19,13,:p_opcion::VARCHAR,:p_controlador::VARCHAR,:p_tooltip::VARCHAR,'',1);");
        $st->bindValue(":p_opc", "A");
        $st->bindValue(":p_opcion", "Administracion roles");
        $st->bindValue(":p_controlador", "/admin/roles");
        $st->bindValue(":p_tooltip", "Administracion roles");

        $st->execute();
        $response = $st->fetchAll();
        return $this->handleView($this->view(array("response"=>"success", "sadas"=>"asdasdas", "asdas"=>array("asdas"=>"asdasdas"))))->setStatusCode(200);
//      return new \Symfony\Component\HttpFoundation\Response(json_encode()), 202);

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
    if(empty($sorting)){
      $sorting["id"] = "asc";
    }
    $serviceMenu = $this->get('appbundle.menu_service');
    $menusOpciones = $serviceMenu->getAllMenuOptions($this->getUser());

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
  public function postProductsAction(Request $request) {
//      if(!$this->isGranted("ROLE_ADMIN")){
//        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
//      }
    $content = json_decode($request->getContent(), true);
    if (empty($content) || !isset($content["title"]) || !isset($content["price"])) {
      return new \Symfony\Component\HttpFoundation\Response(json_encode(["error" => "not enough parameters on the body, you need title and price"]), 401);
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
   *   description = "Create Token",
   *   output = "Array",
   *   authentication = false,
   *   statusCodes = {
   *     200 = "Returned when successful",
   *     404 = "Returned when the page is not found"
   *   }
   * )
   * 
   */
  public function getTokenAction(Request $request) {

    $username = $request->get("username");
    $password = $request->get("password");
    $temp_user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneByUsername($username);
    if($temp_user instanceof \AppBundle\Entity\User){
      $salt = $temp_user->getSalt();
      $salted = $password . '{' . $salt . '}';
    $digest = hash('sha512', $salted, true);

    for ($i = 1; $i < 5000; $i++) {
      $digest = hash('sha512', $digest . $salted, true);
    }

    $encodedPassword = base64_encode($digest);


    $nonce = "60fc915a72194d6f";
    $created = date("Y-m-d\TH:i:s.uP");

    $passwordDigest = base64_encode(sha1(base64_decode($nonce) . $created . $encodedPassword, true));
    $response = ["token"=>'UsernameToken Username="' . $username . '", PasswordDigest="' . $passwordDigest . '", Nonce="' . $nonce . '", Created="' . $created . '"'];
      
    
    return $response;
    }else{
      
      
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
  public function getProductAction($id) {
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
  public function deleteProductAction($id) {
    $product = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Product')
            ->find($id);

    if (!$product) {
      throw $this->createNotFoundException(
              'No product found for id ' . $id
      );
    }

    $em = $this->getDoctrine()->getManager();

    try {
      $em->remove($product);
      $em->flush();
      return new \Symfony\Component\HttpFoundation\Response(json_encode(["success" => "product deleted"]), 200);
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
  public function putProductAction($id) {

//      if(!$this->isGranted("ROLE_ADMIN")){
//        return new \Symfony\Component\HttpFoundation\Response("Autenticación necesaria", 403);
//      }

    try {

      $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
      if (!$product) {
        throw $this->createNotFoundException(
                'No product found for id ' . $id
        );
      }

      $em = $this->getDoctrine()->getManager();

      try {


        $content = json_decode($this->getRequest()->getContent(), true);
        if (isset($content["title"])) {
          $product->setTitle($content["title"]);
        }
        if (isset($content["price"])) {
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
