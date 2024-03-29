<?php

namespace App\Controller\ServiceCommercial;

use App\Entity\Commande;
use App\Entity\Devis;
use App\Entity\LigneCommande;
use App\Entity\LigneDevis;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commercial/commande")
 */
class CommandeController extends AbstractController
{
    private $snappy;
    public function __construct(Pdf $pdf){
        $this->snappy = $pdf;
    }

    /**
     *  Render in a PDF the sandbox_homepage URL
     * @Route("/topdf/{id}", name="commande_to_pdf")
     * @return Response
     */
    public function pdfAction(Commande $commande)
    {
        $this->snappy->setOption('no-outline', true);
        $this->snappy->setOption('page-size','LETTER');
        $this->snappy->setOption('encoding', 'UTF-8');

        $filename = $commande->getLibelle();

        $html = $this->renderView('commande/show_pdf.html.twig', ['commande' => $commande]);

        return new Response(
            $this->snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commande = new Commande();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommandeType::class, $commande, [
            'lignesDevis' => $entityManager->getRepository(Devis::class)->getAllValideLignes(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupere les lignes de devis
            $lignesDevis = [];
            $lignesDevisId = $request->get("commande")['LignesDevis'];
            foreach($lignesDevisId as $ligneId) {
                array_push($lignesDevis, $entityManager->getRepository(LigneDevis::class)->find($ligneId['LigneDevis']));
            }

            //On crée les lignes commandes à partir des lignes de devis
            $lignesCommandes = [];
            /** @var LigneDevis $ligneDevis */
            foreach ($lignesDevis as $ligneDevis) {
                $ligneCommande = new LigneCommande();
                $ligneCommande->setQuantite($ligneDevis->getQuantite());
                $ligneCommande->setPiece($ligneDevis->getPiece());
                $ligneCommande->setPrix($ligneDevis->getPrix());
                $ligneCommande->setCommande($commande);
                array_push($lignesCommandes, $ligneCommande);
            }

            $resLignesCommande = [];
            /** @var LigneCommande $ligne */
            foreach ($lignesCommandes as $ligne) {
                if(isset($resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()])) {
                    $currentLigne = $resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()];
                    $currentLigne->setQuantite($currentLigne->getQuantite() + $ligne->getQuantite());
                }
                else {
                    $resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()] = $ligne;
                }
            }
            $commande->setLignes(new ArrayCollection($resLignesCommande));

            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CommandeType::class, $commande, [
            'lignesDevis' => $entityManager->getRepository(Devis::class)->getAllValideLignes(),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupere les lignes de devis
            $lignesDevis = [];
            $lignesDevisId = $request->request->get("commande")['LignesDevis'];
            foreach($lignesDevisId as $ligneId) {
                array_push($lignesDevis, $entityManager->getRepository(LigneDevis::class)->find($ligneId['LigneDevis']));
            }

            //On crée les lignes commandes à partir des lignes de devis
            $lignesCommandes = [];
            /** @var LigneDevis $ligneDevis */
            foreach ($lignesDevis as $ligneDevis) {
                $ligneCommande = new LigneCommande();
                $ligneCommande->setQuantite($ligneDevis->getQuantite());
                $ligneCommande->setPiece($ligneDevis->getPiece());
                $ligneCommande->setPrix($ligneDevis->getPrix());
                $ligneCommande->setCommande($commande);
                array_push($lignesCommandes, $ligneCommande);
            }
            $allLignes = array_merge($lignesCommandes, $commande->getLignes()->toArray());

            $resLignesCommande = [];
            /** @var LigneCommande $ligne */
            foreach ($allLignes as $ligne) {
                if(isset($resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()])) {
                    $currentLigne = $resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()];
                    $currentLigne->setQuantite($currentLigne->getQuantite() + $ligne->getQuantite());
                }
                else {
                    $resLignesCommande[$ligne->getPiece()->getId().$ligne->getPrix()] = $ligne;
                }
            }
            $commande->getLignes()->clear();
            $entityManager->flush();

            $commande->setLignes(new ArrayCollection($resLignesCommande));
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/validation/{id}", name="commande_validation", methods={"GET"})
     */
    public function validation(CommandeRepository $commandeRepository, Commande $commande): Response
    {
        $commande->setValide(true);
        foreach ($commande->getLignes() as $ligne){
            $ligne->getPiece()->setQuantite($ligne->getPiece()->getQuantite() - $ligne->getQuantite());
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('commande_index');
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }

    /**
     * @Route("/{commande}/{id}", name="commande_ligne_delete", methods={"POST"})
     */
    public function deleteLine(Request $request, LigneCommande $ligneCommande, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneCommande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneCommande);
            $entityManager->flush();
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}
