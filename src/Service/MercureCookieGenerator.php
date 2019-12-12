<?php


namespace App\Service;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureCookieGenerator
{
    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    public function generateAllTracks(): string
    {
        $token = (new Builder())
            ->set('mercure', [ 'subsribe' => ['tracks'] ])
            ->sign(new Sha256(), $this->secret)
            ->getToken();

        return "mercureAuthorization={$token}; Path=/hub; HttpOnly;";
    }
}