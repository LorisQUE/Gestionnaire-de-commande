<?php

namespace App\Controller;

use App\Repository\PieceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @Route("/stock", name="stock")
     */
    public function index(PieceRepository $pieceRepository): Response
    {
        $enumType = array (
            "MP" => "Matière Première",
            "PI" => "Pièce Intermédiaire",
            "PA" => "Pièce Achetée",
            "PL" => "Pièce Livrable"
        );

        return $this->render('stock/index.html.twig', [
            'pieces' => $pieceRepository->findAll(),
            'enumType' => $enumType
        ]);
    }
}
