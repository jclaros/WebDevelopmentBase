<?php

/**
 * Description of WsseListener
 *
 * @author Jonathan Claros <jclaros at lysoftbo.com>
 */

namespace AppBundle\Security\Firewall;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use AppBundle\Security\Authentication\Token\WsseUserToken;

class WsseListener implements ListenerInterface
{
    /**
     * @var $container Container
     */
    protected $container;
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
      
        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            return;
        }
        
        $token = new WsseUserToken();
        $token->setUser($matches[1]);

        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];

        try {
            /* @var $authToken AppBundle\Security\Authentication\Token\WsseUserToken */
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            /**
             * @var $coneccion Connection
             */
            $coneccion = $this->container->get("database_connection");
            $coneccion->close();


            $refCon = new \ReflectionObject($coneccion);

            $refParams = $refCon->getProperty("_params");
            $refParams->setAccessible("public");

            $params = $refParams->getValue($coneccion);
            $params["dbname"] = "base";
            $params["user"] = $matches[1];

            $refParams->setAccessible("private");
            $refParams->setValue($coneccion, $params);

            $this->container->get("doctrine")->resetEntityManager("default");

            return;
        } catch (AuthenticationException $failed) {
            $failedMessage = 'WSSE Login failed for '.$token->getUsername().'. Why ? '.$failed->getMessage();
            
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }

    public function setContainer(Container $container = null){
        $this->container = $container;
    }
}