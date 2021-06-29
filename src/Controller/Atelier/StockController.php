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
     * @param Piece $piece
     */
    public function conformPropertiesToType ($piece) {
        switch ($piece->getType()) {
            case "MP":
            case "PA":
                $piece->setPrix(null);
                $piece->getPiecesNecessaires()->clear();
                break;

            case "PL":
                $piece->setFournisseur(null);
                $piece->setPrixCatalogue(null);
                $piece->getPiecesProduites()->clear();
                break;

            case "PI":
                $piece->setFournisseur(null);
                $piece->setPrixCatalogue(null);
                $piece->setPrix(null);
                break;
        }
    }

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
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($entityManager->getRepository(Piece::class)->findAll() as $pPiece) {
                if($pPiece->getReference() === $piece->getReference()){
                    $this->addFlash(
                        'warning',
                        'Cette référence est déjà utilisée'
                    );

                    return $this->render('stock/new.html.twig', [
                        'piece' => $piece,
                        'form' => $form->createView(),
                    ]);
                }
            }

            //Pièces Nécessaire
            $resNecessaires = [];

            foreach ($piece->getPiecesNecessaires() as $pn){
                if(isset($resNecessaires[$pn->getPieceNecessaire()->getId()])) {
                    $var = $resNecessaires[$pn->getPieceNecessaire()->getId()];
                    $var->setQuantite($var->getQuantite() + $pn->getQuantite());
                } else {
                    $resNecessaires[$pn->getPieceNecessaire()->getId()] = $pn;
                }
            }

            $piece->getPiecesNecessaires()->clear();

            foreach($resNecessaires as $r){
                $piece->addPiecesNecessaire($r);
            }

            //Pièces produites
            $resProduites = [];

            foreach ($piece->getPiecesProduites() as $pp){
                if(isset($resProduites[$pp->getPieceProduite()->getId()])) {
                    $var = $resProduites[$pp->getPieceProduite()->getId()];
                    $var->setQuantite($var->getQuantite() + $pp->getQuantite());
                } else {
                    $resProduites[$pp->getPieceProduite()->getId()] = $pp;
                }
            }

            $piece->getPiecesProduites()->clear();

            foreach($resProduites as $r){
                $piece->addPiecesProduite($r);
            }

            $piece->setPrix($piece->getPrix() * 1.2);

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
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($entityManager->getRepository(Piece::class)->findAll() as $pPiece) {
                if($pPiece->getReference() === $piece->getReference()){
                    $this->addFlash(
                        'warning',
                        'Cette référence est déjà utilisée'
                    );

                    return $this->render('stock/new.html.twig', [
                        'piece' => $piece,
                        'form' => $form->createView(),
                    ]);
                }
            }

            $this->conformPropertiesToType($piece);
            foreach ($piece->getPiecesNecessaires() as $pn){
                if($pn->getId() === null) $entityManager->persist($pn);
            }
            foreach ($piece->getPiecesProduites() as $pp){
                if($pp->getId() === null) $entityManager->persist($pp);
            }

            $piece->setPrix($piece->getPrix() * 1.2);

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
