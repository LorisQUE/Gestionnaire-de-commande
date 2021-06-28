<?php

namespace App\Controller\ServiceCommercial;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commercial/devis")
 */
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="devis_index", methods={"GET"})
     */
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="devis_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($devi->getLignes() as $ligne){
                $ligne->setPrix($ligne->getPiece()->getPrix());
            }

            //Ajout en quantité de commande, pour pas dupliqué la ligne
            $resLignes = [];

            foreach ($devi->getLignes() as $ligne){
                if(isset($resLignes[$ligne->getPiece()->getId()])) {
                    $var = $resLignes[$ligne->getPiece()->getId()];
                    $var->setQuantite($var->getQuantite() + $ligne->getQuantite());
                } else {
                    $resLignes[$ligne->getPiece()->getId()] = $ligne;
                }
            }

            $devi->getLignes()->clear();

            foreach($resLignes as $r){
                $devi->addLigne($r);
            }
            $entityManager->persist($devi);
            $entityManager->flush();

            return $this->redirectToRoute('devis_index');
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_show", methods={"GET"})
     */
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="devis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Devis $devi): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {$entityManager = $this->getDoctrine()->getManager();
            foreach ($devi->getLignes() as $ligne){
                $ligne->setPrix($ligne->getPiece()->getPrix());
            }

            //Ajout en quantité de commande, pour pas dupliqué la ligne
            $resLignes = [];

            foreach ($devi->getLignes() as $ligne){
                if(isset($resLignes[$ligne->getPiece()->getId()])) {
                    $var = $resLignes[$ligne->getPiece()->getId()];
                    $var->setQuantite($var->getQuantite() + $ligne->getQuantite());
                } else {
                    $resLignes[$ligne->getPiece()->getId()] = $ligne;
                }
            }

            $devi->getLignes()->clear();

            foreach($resLignes as $r){
                $devi->addLigne($r);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devis_index');
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_delete", methods={"POST"})
     */
    public function delete(Request $request, Devis $devi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('devis_index');
    }
}
