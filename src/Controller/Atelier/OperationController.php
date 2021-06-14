<?php

namespace App\Controller\Atelier;

use App\Entity\Gamme;
use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\GammeRepository;
use App\Repository\OperationRepository;
use phpDocumentor\Reflection\Types\Integer;
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
}
