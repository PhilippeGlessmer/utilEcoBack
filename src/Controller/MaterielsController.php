<?php

namespace App\Controller;

use App\Entity\Materiels;
use App\Form\MaterielsType;
use App\Repository\MaterielsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/materiels")
 */
class MaterielsController extends AbstractController
{
    /**
     * @Route("/", name="materiels_index", methods={"GET"})
     */
    public function index(MaterielsRepository $materielsRepository): Response
    {
        return $this->render('materiels/index.html.twig', [
            'materiels' => $materielsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="materiels_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $materiel = new Materiels();
        $form = $this->createForm(MaterielsType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            return $this->redirectToRoute('materiels_index');
        }

        return $this->render('materiels/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiels_show", methods={"GET"})
     */
    public function show(Materiels $materiel, Session $session): Response
    {
        $session->set('id', $materiel->getId());
        return $this->render('materiels/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiels_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Materiels $materiel): Response
    {
        $form = $this->createForm(MaterielsType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('materiels_index');
        }

        return $this->render('materiels/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiels_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Materiels $materiel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materiel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('materiels_index');
    }
}
