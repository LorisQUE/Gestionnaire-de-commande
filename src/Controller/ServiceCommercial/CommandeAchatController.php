<?php

namespace App\Controller\ServiceCommercial;

use App\Entity\CommandeAchat;
use App\Form\CommandeAchatNewType;
use App\Form\CommandeAchatType;
use App\Repository\CommandeAchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commercial/commande")
 */
class CommandeAchatController extends AbstractController
{
    /**
     * @Route("/", name="commande_achat_index", methods={"GET"})
     */
    public function index(CommandeAchatRepository $commandeAchatRepository): Response
    {
        return $this->render('commande_achat/index.html.twig', [
            'commande_achats' => $commandeAchatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commande_achat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commandeAchat = new CommandeAchat();
        $form = $this->createForm(CommandeAchatType::class, $commandeAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($commandeAchat->getLignes() as $ligne){
                $ligne->setPrix($ligne->getPiece()->getPrixCatalogue());
            }

            //Ajout en quantité de commande, pour pas dupliqué la ligne
            $resLignes = [];

            foreach ($commandeAchat->getLignes() as $ligne){
                if(isset($resLignes[$ligne->getPiece()->getId()])) {
                    $var = $resLignes[$ligne->getPiece()->getId()];
                    $var->setQuantite($var->getQuantite() + $ligne->getQuantite());
                } else {
                    $resLignes[$ligne->getPiece()->getId()] = $ligne;
                }
            }

            $commandeAchat->getLignes()->clear();

            foreach($resLignes as $r){
                $commandeAchat->addLigne($r);
            }

            $entityManager->persist($commandeAchat);
            $entityManager->flush();

            return $this->redirectToRoute('commande_achat_index');
        }

        return $this->render('commande_achat/new.html.twig', [
            'commande_achat' => $commandeAchat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_achat_show", methods={"GET"})
     */
    public function show(CommandeAchat $commandeAchat): Response
    {
        return $this->render('commande_achat/show.html.twig', [
            'commande_achat' => $commandeAchat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_achat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommandeAchat $commandeAchat): Response
    {
        $form = $this->createForm(CommandeAchatType::class, $commandeAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($commandeAchat->getLignes() as $ligne){
                $ligne->setPrix($ligne->getPiece()->getPrixCatalogue());
            }

            //Ajout en quantité de commande, pour pas dupliqué la ligne
            $resLignes = [];

            foreach ($commandeAchat->getLignes() as $ligne){
                if(isset($resLignes[$ligne->getPiece()->getId()])) {
                    $var = $resLignes[$ligne->getPiece()->getId()];
                    $var->setQuantite($var->getQuantite() + $ligne->getQuantite());
                } else {
                    $resLignes[$ligne->getPiece()->getId()] = $ligne;
                }
            }

            $commandeAchat->getLignes()->clear();

            foreach($resLignes as $r){
                $commandeAchat->addLigne($r);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_achat_index');
        }

        return $this->render('commande_achat/edit.html.twig', [
            'commande_achat' => $commandeAchat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_achat_delete", methods={"POST"})
     */
    public function delete(Request $request, CommandeAchat $commandeAchat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeAchat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commandeAchat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_achat_index');
    }
}
