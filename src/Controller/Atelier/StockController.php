<?php

namespace App\Controller\Atelier;

use App\Entity\Piece;
use App\Form\StockType;
use App\Repository\PieceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atelier/stock")
 */
class StockController extends AbstractController
{

    public $enumType = array (
        "MP" => "Matière Première",
        "PA" => "Pièce Achetée",
        "PI" => "Pièce Intermédiaire",
        "PL" => "Pièce Livrable"
    );

    /**
     * @Route("/", name="stock")
     */
    public function index(PieceRepository $pieceRepository): Response
    {
        return $this->render('stock/index.html.twig', [
            'pieces' => $pieceRepository->findAll(),
            'enumType' => $this->enumType,
        ]);
    }

    /**
     * @Route("/new", name="stock_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $piece = new Piece();
        $form = $this->createForm(StockType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($piece);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piece);
            $entityManager->flush();

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/new.html.twig', [
            'piece' => $piece,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_show", methods={"GET"})
     */
    public function show(Piece $piece): Response
    {
        $pieceNecessaire = $piece->getPiecesNecessaires();
        $pieceRealisable = $piece->getPiecesProduites();

        foreach ($pieceNecessaire as $p) {
            dump($piece->getid(), $p->getQuantite());
        }

        return $this->render('stock/show.html.twig', [
            'pieceNecessaire' => $pieceNecessaire,
            'pieceRealisable' => $pieceRealisable,
            'piece' => $piece,
            'enumType' => $this->enumType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Piece $piece): Response
    {
        $form = $this->createForm(StockType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($piece);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stock');
        }

        return $this->render('stock/edit.html.twig', [
            'piece' => $piece,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"POST"})
     */
    public function delete(Request $request, Piece $piece): Response
    {
        if ($this->isCsrfTokenValid('delete'.$piece->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($piece);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock');
    }
}
