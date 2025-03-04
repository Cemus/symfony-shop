<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    #[Route(path:'/calculator/{nbr1}/{nbr2}/{operator}', name: 'app_calculator')]
    public function calculator(mixed $nbr1, mixed $nbr2, string $operator) : Response {
        $errorMessage = "";
        $result = 0;
        if(!is_numeric($nbr1) || !is_numeric($nbr2)) {
            $errorMessage = "Les deux nombres ne sont pas numériques";
        }
        else {
            switch ($operator) {
                case 'add':
                    $result = $nbr1 + $nbr2;
                    break;
                case 'sous':
                    $result = $nbr1 - $nbr2;
                    break;
                case 'multi':
                    $result = $nbr1 * $nbr2;
                    break;
                case 'div':
                    if($nbr2 === 0) {
                        $errorMessage = "Division par zéro impossible";
                    }else{
                        $result = $nbr1 / $nbr2;
                    }
                    break;
                default:
                    $errorMessage = "Opérateur inconnu";
                    break;
            }
        }
        switch ($operator){
            case "add":
                $operator = "+";
                break;
            case "sous":
                $operator = "-";
                break;
            case "multi":
                $operator = "*";
                break;
            case "div":
                $operator = "/";
                break;
            default:
                $operator = "";
                break;
        }
        return $this->render('calculator.html.twig', [
            "nbr1" => $nbr1,
            "nbr2" => $nbr1,
            "operator" => $operator,
            "result" => $result,
            "errorMessage" => $errorMessage,
        ]);
    }
}