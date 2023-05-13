<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Joke;

class JokesController extends AbstractController
{
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
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

    #[Route('/listJokes', name: 'listJokes', methods: ['GET'])]
    public function getJokes(EntityManagerInterface $doctrine): Response
    {
        $repository = $doctrine->getRepository(Joke::class);
        $jokes = $repository->findBy([], ['numberJoke' => 'ASC']);

        return $this->render("jokes/myJokes.html.twig", [
            'jokes' => $jokes,
        ]);
    }

    #[Route('/addJoke', name: 'add_joke', methods: ['GET', 'POST'])]
public function addJoke(Request $request): Response
{
    if ($request->isMethod('POST')) {
        $joke = $request->request->get('joke');
        $numberJoke = $request->request->get('numberJoke');

        $jokeEntity = new Joke();
        $jokeEntity->setJoke($joke);
        $jokeEntity->setNumberJoke($numberJoke);

        $this->entityManager->persist($jokeEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute('listJokes');
    }

    return $this->render('jokes/addJoke.html.twig');
}

    #[Route('/deleteJoke/{numberJoke}', name: 'delete_joke', methods: ['GET'])]
    public function deleteJoke(int $numberJoke, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Joke::class);
        $joke = $repository->findOneBy(['numberJoke' => $numberJoke]);

        if ($joke) {
            $entityManager->remove($joke);
            $entityManager->flush();
        }

        return $this->redirectToRoute('listJokes');
    }

    #[Route('/editJoke/{numberJoke}', name: 'edit_joke', methods: ['GET', 'POST'])]
    public function editJoke(Request $request, int $numberJoke, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Joke::class);
        $joke = $repository->findOneBy(['numberJoke' => $numberJoke]);
            
        // if (!$joke) {
            
        // }
    
        if ($request->isMethod('POST')) {
            $newJoke = $request->request->get('joke');
    
            $joke->setJoke($newJoke);
            $entityManager->flush();
    
            return $this->redirectToRoute('listJokes');
        }
    
        return $this->render("jokes/addJoke.html.twig", [
            'joke' => $joke ? $joke->getJoke() : null,
            'numberJoke' => $numberJoke,
        ]);
    }

}
