<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokesController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/inicio', name: 'jokeRandom')]
    public function inicio(): Response
    {
        $response = $this->httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
        $data = $response->toArray();

        $chiste = $data['value'];

        return $this->render("jokes/inicioJokes.html.twig", [
            'chiste' => $chiste
        ]);
    }
}
