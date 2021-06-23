<?php

namespace App\Controller\Atelier;

use App\Entity\Gamme;
use App\Entity\GammeRealisation;
use App\Entity\OperationRealisation;
use App\Form\GammeRealisationNewType;
use App\Form\GammeRealisationType;
use App\Form\GammeType;
use App\Repository\GammeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atelier/gamme")
 */
class GammeController extends AbstractController
{
    //Gammes :

    /**
     * @Route("/", name="gamme_index", methods={"GET"})
     */
    public function index(GammeRepository $gammeRepository): Response
    {
        return $this->render('gamme/index.html.twig', [
            'gammes' => $gammeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gamme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamme = new Gamme();
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gamme);
            $entityManager->flush();

            return $this->redirectToRoute('gamme_index');
        }

        return $this->render('gamme/new.html.twig', [
            'gamme' => $gamme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gamme_show", methods={"GET"})
     */
    public function show(Gamme $gamme): Response
    {
        $operations = $gamme->getOperations();
        return $this->render('gamme/show.html.twig', [
            'operations' => $operations,
            'gamme' => $gamme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gamme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gamme $gamme): Response
    {
        $form = $this->createForm(GammeType::class, $gamme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamme_index');
        }

        return $this->render('gamme/edit.html.twig', [
            'gamme' => $gamme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gamme_delete", methods={"POST"})
     */
    public function delete(Request $request, Gamme $gamme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gamme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gamme_index');
    }

    // Réalisations des Gammes :

    /**
     * @Route("/{id}/realisation", name="gamme_real", methods={"GET"})
     */
    public function showReal(Gamme $gamme): Response
    {
        return $this->render('gamme/realisations.html.twig', [
            'gamme' => $gamme,
            'realisations' => $gamme->getGammeRealisations(),
        ]);
    }

    /**
     * @Route("/{id}/edit/realisation", name="gamme_realisation_edit", methods={"GET","POST"})
     */
    public function editReal(Request $request, GammeRealisation $gammeRealisation): Response
    {
        $form = $this->createForm(GammeRealisationType::class, $gammeRealisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamme_real', ['id'=> $gammeRealisation->getGamme()->getId()]);
        }

        return $this->render('gamme/edit_realisation.html.twig', [
            'gamme_realisation' => $gammeRealisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/new/realisation", name="gamme_new_real",  methods={"GET","POST"})
     * @param Request $request
     * @param Gamme $gamme
     * @return Response
     */
    public function newReal(Request $request, Gamme $gamme): Response
    {
        //On créer la nouvelle réalisation de gamme
        $gammeReal = new GammeRealisation();
        $gammeReal->setLibelle($gamme->getLibelle());
        $gammeReal->setSuperviseur($gamme->getSuperviseur());
        $gammeReal->setGamme($gamme);

        //On créer les nouvelles réalisation d'opérations pour la réalisation de gamme
        foreach ($gamme->getOperations() as $operation){
            $opeReal = new OperationRealisation();
            $opeReal->setOperation($operation);
            $opeReal->setLibelle($operation->getLibelle());
            $opeReal->setDuree($operation->getDuree());
            $opeReal->setPosteDeTravail($operation->getPosteDeTravail());
            $opeReal->setMachine($operation->getMachine());

            $opeReal->setOperateur(null);

            $gammeReal->addOperationRealisation($opeReal);
            $opeReal->setGammeRealisation($gammeReal);
        }

        $form = $this->createForm(GammeRealisationNewType::class, $gammeReal);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            return $this->render('gamme/_form_realisation_new.html.twig', [
                'form' => $form->createView()
            ]);
        }

        if ($form->isSubmitted() && $form->isValid() && !$request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($gammeReal->getOperationRealisations() as $operation){
                $entityManager->persist($operation);
            }
            $entityManager->persist($gammeReal);
            $entityManager->flush();

            return $this->redirectToRoute('gamme_real', ['id'=> $gamme->getId()]);
        }

        return $this->render('gamme/realisation_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete/realisation", name="gamme_realisation_delete", methods={"POST"})
     */
    public function deleteReal(Request $request, GammeRealisation $gammeRealisation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gammeRealisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gammeRealisation);
            $entityManager->flush();
        }


        return $this->redirectToRoute('gamme_real', ['id'=> $gammeRealisation->getGamme()->getId()]);
    }
}
