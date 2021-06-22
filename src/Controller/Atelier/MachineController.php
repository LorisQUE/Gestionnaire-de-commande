<?php

namespace App\Controller\Atelier;

use App\Entity\Machine;
use App\Entity\PosteDeTravail;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/atelier/machine")
 */
class MachineController extends AbstractController
{

    /**
     * @Route("{poste}/new", name="machine_new", methods={"GET","POST"})
     */
    public function new(Request $request, PosteDeTravail $poste): Response
    {
        $machine = new Machine();
        $machine->setPosteDeTravail($poste);
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($machine);
            $entityManager->flush();

            return $this->redirectToRoute('poste_show', ['id'=> $poste->getId()]);
        }

        return $this->render('machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="machine_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Machine $machine): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poste_show', ['id'=> $machine->getPosteDeTravail()->getId()]);
        }

        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="machine_delete", methods={"POST"})
     */
    public function delete(Request $request, Machine $machine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$machine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($machine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poste_show', ['id'=> $machine->getPosteDeTravail()->getId()]);
    }
}
