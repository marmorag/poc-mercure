<?php


namespace App\Controller;


use App\Service\MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/home", name="home")
     *
     * @param MercureCookieGenerator $generator
     * @return Response
     */
    public function index(MercureCookieGenerator $generator): Response
    {
        $response = $this->render('home.html.twig');
        $response->headers->set('set-cookie', $generator->generateAllTracks());

        return $response;
    }
}