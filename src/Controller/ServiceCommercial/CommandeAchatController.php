<?php

namespace App\Controller\ServiceCommercial;

use App\Entity\CommandeAchat;
use App\Form\CommandeAchatNewType;
use App\Form\CommandeAchatType;
use App\Repository\CommandeAchatRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/commercial/commande-achat")
 */
class CommandeAchatController extends AbstractController
{
    private $snappy;
    public function __construct(Pdf $pdf){
        $this->snappy = $pdf;
    }

    /**
     *  Render in a PDF the sandbox_homepage URL
     * @Route("/topdf/{id}", name="commande_achat_to_pdf")
     * @return Response
     */
    public function pdfAction(CommandeAchat $commandeAchat)
    {
        $this->snappy->setOption('no-outline', true);
        $this->snappy->setOption('page-size','LETTER');
        $this->snappy->setOption('encoding', 'UTF-8');

        $filename = $commandeAchat->getLibelle();

        $html = $this->renderView('commande_achat/show_pdf.html.twig', ['commande_achat' => $commandeAchat]);

        return new Response(
            $this->snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }

    public function validerCommande(CommandeAchat $commandeAchat){
        //Ajout de la quantité des pièces
        foreach ($commandeAchat->getLignes() as $ligne){
            $currentQte = $ligne->getPiece()->getQuantite();
            $ligne->getPiece()->setQuantite($currentQte + $ligne->getQuantite());
        }
    }

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

            if($commandeAchat->getDateEffective())
                $this->validerCommande($commandeAchat);

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

            if($commandeAchat->getDateEffective())
                $this->validerCommande($commandeAchat);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_achat_index');
        }

        return $this->render('commande_achat/edit.html.twig', [
            'commande_achat' => $commandeAchat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/validation/{id}", name="commande_achat_validation", methods={"POST"})
     */
    public function validation(Request $request, CommandeAchat $commandeAchat): Response
    {
        $dateEffective = new \DateTime($request->get("dateValidation"));
        $commandeAchat->setDateEffective($dateEffective);
        $this->validerCommande($commandeAchat);
        $this->getDoctrine()->getManager()->persist($commandeAchat);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse();
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
