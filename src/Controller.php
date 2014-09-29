<?php

namespace app\oauth2;

use infuse\Model;
use OAuth2\GrantType\UserCredentials;
use OAuth2\ResponseType\JwtAccessToken;
use OAuth2\Storage\Memory;
use OAuth2\Storage\JwtAccessToken as JwtAccessTokenStorage;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\Server;

use app\auth\libs\Auth;
use app\oauth2\libs\IdealistStorage;

class Controller
{
    use \InjectApp;

    public function middleware($req, $res)
    {
        $this->app['oauth_server'] = function ($c) {
            $storage = new IdealistStorage();
            $storage->injectApp($c);

            $server = new Server($storage);

            // password grant type
            $grantType = new UserCredentials($storage);
            $server->addGrantType($grantType);

            // JWT access token response type
            $config = $c['config']->get('oauth2');
            $jwtResponseType = new JwtAccessToken($storage, $storage, null, $config);
            $server->addResponseType($jwtResponseType);

            return $server;
        };

        $this->app['oauth_resource'] = function ($c) {
            $server = new Server();

            // no private key is necessary for the resource server
            $keyStorage = new Memory([
                'keys' => [
                    'public_key' => file_get_contents(INFUSE_BASE_DIR . '/jwt_pubkey.pem')]]);

            $storage = new JwtAccessTokenStorage($keyStorage);
            $server->addStorage($storage, 'access_token');

            return $server;
        };

        // attempt to authenticate the user when an API request is made
        if ($req->isApi())
            $this->authenticateApiRequest();
    }

    public function token($req, $res)
    {
        $request = Request::createFromGlobals();
        $this->app['oauth_server']->handleTokenRequest($request)->send();
        exit;
    }

    public function tokenSignOut($req, $res)
    {
        // do nothing for now
        $res->json(['success'=>true]);
    }

    private function authenticateApiRequest()
    {
        $resource = $this->app['oauth_resource'];
        $request = Request::createFromGlobals();
        $response = new Response();

        if ($resource->verifyResourceRequest($request, $response)) {
            $tokenData = $resource->getResourceController()->getToken();

            // replace current user with the user from the access token
            $userModel = Auth::USER_MODEL;
            $user = $this->app['user'] = new $userModel($tokenData['user_id'], true);

            // use the authenticated user as the requester for model permissions
            Model::configure(['requester' => $user]);
        } else {
            $response->send();
            exit;
        }
    }
}
