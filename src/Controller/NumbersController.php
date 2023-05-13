<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NumbersController extends AbstractController
{
    #[Route('/number/inc/{number}', name: 'increment')]
    public function index(Request $request, int $number): Response
    {
        $numberInc = $number + 1;

        return $this->render('numbers/increment.html.twig', [
            'numberInc' => $numberInc,
        ]);
    }
}
