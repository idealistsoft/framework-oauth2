<?php

namespace app\oauth2\libs;

use OAuth2\Storage\AccessTokenInterface;
use OAuth2\Storage\ClientCredentialsInterface;
use OAuth2\Storage\PublicKeyInterface;
use OAuth2\Storage\UserCredentialsInterface;
use OAuth2\OpenID\Storage\UserClaimsInterface;

use app\users\models\User;

class IdealistStorage implements UserCredentialsInterface, ClientCredentialsInterface,
    AccessTokenInterface, UserClaimsInterface, PublicKeyInterface
{
    use \InjectApp;

    private $user;
    private $accessTokens = [];

    //////////////////////
    // Client
    //////////////////////

    public function getClientDetails($client_id)
    {
        // NOT IMPLEMENTED
        if ($client_id == 'test') {
            return [
                'redirect_uri' => 'not_implemented',
                'client_id' => 'test',
                'grant_types' => ['password'],
                'scope' => '' ];
        }

        return false;
    }

    public function getClientScope($client_id)
    {
        $clientDetails = $this->getClientDetails($client_id);

        return $clientDetails['scope'];
    }

    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $clientDetails = $this->getClientDetails($client_id);

        return in_array($grant_type, $clientDetails['grant_types']);
    }

    //////////////////////
    // PublicKey
    //////////////////////

    public function getPublicKey($client_id = null)
    {
        return file_get_contents(INFUSE_BASE_DIR . '/jwt_pubkey.pem');
    }

    public function getPrivateKey($client_id = null)
    {
        return file_get_contents(INFUSE_BASE_DIR . '/jwt_privkey.pem');
    }

    public function getEncryptionAlgorithm($client_id = null)
    {
        return 'RS256';
    }

    //////////////////////
    // ClientCredentials
    //////////////////////

    public function checkClientCredentials($client_id, $client_secret = null)
    {
        // NOT IMPLEMENTED
        return false;
    }

    public function isPublicClient($client_id)
    {
        // NOT IMPLEMENTED
        return true;
    }

    //////////////////////
    // UserCredentials
    //////////////////////

    public function checkUserCredentials($username, $password)
    {
        $this->user = $this->app['auth']->getUserWithCredentials($username, $password);

        return $this->user instanceof User;
    }

    public function getUserDetails($username)
    {
        // we can do this because checkUserCredentials will be called first
        if ($this->user)
            return ['user_id' => $this->user->id()];

        return false;
    }

    //////////////////////
    // UserClaims
    //////////////////////

    public function getUserClaims($user_id, $scope)
    {
        // NOT IMPLEMENTED
        return [];
    }

    //////////////////////
    // AccessToken
    //////////////////////

    public function getAccessToken($access_token)
    {
        return isset($this->accessTokens[$access_token]) ? $this->accessTokens[$access_token] : false;
    }

    public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null, $id_token = null)
    {
        $this->accessTokens[$access_token] = compact('access_token', 'client_id', 'user_id', 'expires', 'scope', 'id_token');

        // sign in the user
        $this->app['auth']->signInUser($user_id, 'oauth2');

        return true;
    }
}
