<?php

/**
 * Description of WsseProvider
 * WsseProvider is the responsible for authenticate a defined object called token, it starts with authenticate
 * and concludes with validateDigest
 *
 * @author Jonathan Claros <jclaros at lysoftbo.com>
 */

namespace AppBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Util\StringUtils;

class WsseProvider implements AuthenticationProviderInterface {

  private $userProvider;
  private $cacheDir;

  public function __construct(UserProviderInterface $userProvider, $cacheDir) {
    $this->userProvider = $userProvider;
    $this->cacheDir = $cacheDir;
  }

  public function authenticate(TokenInterface $token) {
    $user = $this->userProvider->loadUserByUsername($token->getUsername());
    if (!$user) {
      throw new AuthenticationException("Bad credentials... Did you forgot your username ?");
    }

    if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
      $authenticatedToken = new WsseUserToken($user->getRoles());
      $authenticatedToken->setUser($user);

      return $authenticatedToken;
    }

    throw new AuthenticationException('The WSSE authentication failed.');
  }

  /**
   * This function is specific to Wsse authentication and is only used to help this example
   *
   * For more information specific to the logic here, see
   * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
   */
  protected function validateDigest($digest, $nonce, $created, $secret) {
    // Check created time is not in the future
    if (strtotime($created) > time()) {
      throw new AuthenticationException("Back to the future...");
    }

    // Expire timestamp after 50 minutes
    if (time() - strtotime($created) > 30000) {
      throw new AuthenticationException("Too late for this timestamp... Watch your watch.");
    }

    // Validate nonce is unique within 50 minutes
    if (file_exists($this->cacheDir . '/' . $nonce) && file_get_contents($this->cacheDir . '/' . $nonce) + 30000 > time()) {
      //throw new NonceExpiredException('Previously used nonce detected');
    }

    // If cache directory does not exist we create it
    if (!is_dir($this->cacheDir)) {
      mkdir($this->cacheDir, 0777, true);
    }

    file_put_contents($this->cacheDir . '/' . $nonce, time());

    // Validate Secret
    $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));
    
    if ($digest !== $expected) {
      throw new AuthenticationException("Bad credentials ! Digest is not as expected.");
    }

    return true;
  }

  public function supports(TokenInterface $token) {
    return $token instanceof WsseUserToken;
  }

}
