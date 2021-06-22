<?php

namespace App\Controller\Atelier;

use App\Entity\PosteDeTravail;
use App\Form\PosteDeTravailType;
use App\Repository\PosteDeTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atelier/poste")
 */
class PosteDeTravailController extends AbstractController
{
    /**
     * @Route("/", name="poste_index", methods={"GET"})
     */
    public function index(PosteDeTravailRepository $posteDeTravailRepository): Response
    {
        return $this->render('poste_de_travail/index.html.twig', [
            'poste_de_travails' => $posteDeTravailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="poste_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $posteDeTravail = new PosteDeTravail();
        $form = $this->createForm(PosteDeTravailType::class, $posteDeTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($posteDeTravail);
            $entityManager->flush();

            return $this->redirectToRoute('poste_index');
        }

        return $this->render('poste_de_travail/new.html.twig', [
            'poste_de_travail' => $posteDeTravail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poste_show", methods={"GET"})
     */
    public function show(PosteDeTravail $posteDeTravail): Response
    {
        $machines = $posteDeTravail->getMachines();
        $ouvriers = $posteDeTravail->getOuvriers();
        return $this->render('poste_de_travail/show.html.twig', [
            'poste_de_travail' => $posteDeTravail,
            'machines' => $machines,
            'ouvriers' => $ouvriers,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="poste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PosteDeTravail $posteDeTravail): Response
    {
        $form = $this->createForm(PosteDeTravailType::class, $posteDeTravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poste_index');
        }

        return $this->render('poste_de_travail/edit.html.twig', [
            'poste_de_travail' => $posteDeTravail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poste_delete", methods={"POST"})
     */
    public function delete(Request $request, PosteDeTravail $posteDeTravail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$posteDeTravail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($posteDeTravail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poste_index');
    }
}
