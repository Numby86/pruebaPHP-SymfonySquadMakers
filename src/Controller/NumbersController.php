<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NumbersController extends AbstractController
{
    #[Route('/increment/{number}', name: 'increment')]
    public function increment(Request $request, $number): Response
    {
        if (!is_numeric($number)) {
            $numberInc = 'No es un número';
        } else {
            $numberInc = $number + 1;
        }

        return $this->render('numbers/increment.html.twig', [
            'numberInc' => $numberInc,
        ]);
    }

    #[Route('/mcmNumbers/{numbers}', name: 'mcm')]
    public function mcmNumbers(Request $request, $numbers): Response
    {
        $numbersArray = explode(',', $numbers);
        $numbersArray = array_map('intval', $numbersArray);
    
        $mcm = $this->calculateMCM($numbersArray);
    
        return $this->render('numbers/mcmNumbers.html.twig', [
            'mcm' => $mcm,
        ]);
    }
    
    private function calculateMCM(array $numbers): int
    {
        $mcm = $numbers[0];
    
        foreach ($numbers as $number) {
            $mcm = $this->calculateLCM($mcm, $number);
        }
    
        return $mcm;
    }
    
    private function calculateLCM($a, $b): int
    {
        return abs($a * $b) / $this->calculateGCD($a, $b);
    }
    
    private function calculateGCD($a, $b): int
    {
        while ($b != 0) {
            $temp = $a % $b;
            $a = $b;
            $b = $temp;
        }
    
        return $a;
    }
    
}
