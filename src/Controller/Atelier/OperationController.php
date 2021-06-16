<?php

namespace App\Controller\Atelier;

use App\Entity\Gamme;
use App\Entity\GammeRealisation;
use App\Entity\Operation;
use App\Entity\OperationRealisation;
use App\Form\OperationRealisationType;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atelier/operation")
 */
class OperationController extends AbstractController
{
    /**
     * @Route("/", name="operation_index", methods={"GET"})
     */
    public function index(OperationRepository $operationRepository): Response
    {
        return $this->render('operation/index.html.twig', [
            'operations' => $operationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{gamme}/new", name="operation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Gamme $gamme): Response
    {
        $operation = new Operation();
        $operation->setGamme($gamme);
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            $entityManager->flush();
            return $this->redirectToRoute('gamme_show', ['id' => $gamme->getId()]);
        }

        return $this->render('operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operation_show", methods={"GET"})
     */
    public function show(Operation $operation): Response
    {
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    /**
     * @Route("/{id}/edit/{gamme}", name="operation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Operation $operation, Gamme $gamme): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamme_show', ['id' => $gamme->getId()]);
        }

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{gamme}", name="operation_delete", methods={"POST"})
     */
    public function delete(Request $request, Operation $operation, Gamme $gamme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gamme_show', ['id' => $gamme->getId()]);
    }

    // Réalisation d'opération
    /**
     * @Route("/{id}/realisation/edit", name="operation_edit_real", methods={"GET","POST"})
     */
    public function editReal(Request $request, OperationRealisation $operationRealisation): Response
    {
        $form = $this->createForm(OperationRealisationType::class, $operationRealisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operation_real', ['id'=> $operationRealisation->getGammeRealisation()->getId()]);
        }

        return $this->render('operation/edit_realisation.html.twig', [
            'operation_realisation' => $operationRealisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/realisation", name="operation_real", methods={"GET"})
     */
    public function showReal(GammeRealisation $gammeRealisation): Response
    {
        $operationsRealisations = $gammeRealisation->getOperationRealisations();
        return $this->render('operation/realisations.html.twig', [
            'operationsRealisations' => $operationsRealisations,
            'gammeRealisation' => $gammeRealisation,
        ]);
    }
}
