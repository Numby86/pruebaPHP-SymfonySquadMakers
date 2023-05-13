<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokesController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'jokeRandom')]
    public function inicio(): Response
    {
        $response = $this->httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
        $data = $response->toArray();

        $chiste = $data['value'];

        return $this->render("jokes/inicioJokes.html.twig", [
            'chiste' => $chiste
        ]);
    }

    #[Route('/chuck-or-dad/{tipo}', name: 'chuck_or_dad_joke')]
    public function chuckOrDadJoke(Request $request, string $tipo): Response
    {
        if ($tipo === 'Chuck') {
            $response = $this->httpClient->request('GET', 'https://api.chucknorris.io/jokes/random');
            $data = $response->toArray();
            $chiste = $data['value'];
        } elseif ($tipo === 'Dad') {
            $response = $this->httpClient->request('GET', 'https://icanhazdadjoke.com/', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            $data = $response->toArray();
            $chiste = $data['joke'];
        } else {
            $chiste = 'Tipo de chiste inválido';
        }
    
        return $this->render("jokes/chuckOrDad.html.twig", [
            'chiste' => $chiste , 
            'tipo' => $tipo
        ]);
    }
}
