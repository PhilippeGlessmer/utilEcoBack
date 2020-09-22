<?php

namespace App\Controller;

use App\Entity\Pointscollects;
use App\Form\PointscollectsType;
use App\Repository\PointscollectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/pointscollects")
 */
class PointscollectsController extends AbstractController
{
    /**
     * @Route("/", name="pointscollects_index", methods={"GET"})
     */
    public function index(PointscollectsRepository $pointscollectsRepository): Response
    {
        return $this->render('pointscollects/index.html.twig', [
            'pointscollects' => $pointscollectsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pointscollects_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pointscollect = new Pointscollects();
        $form = $this->createForm(PointscollectsType::class, $pointscollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pointscollect);
            $entityManager->flush();

            return $this->redirectToRoute('pointscollects_index');
        }

        return $this->render('pointscollects/new.html.twig', [
            'pointscollect' => $pointscollect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pointscollects_show", methods={"GET"})
     */
    public function show(Pointscollects $pointscollect): Response
    {
        return $this->render('pointscollects/show.html.twig', [
            'pointscollect' => $pointscollect,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pointscollects_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pointscollects $pointscollect): Response
    {
        $form = $this->createForm(PointscollectsType::class, $pointscollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pointscollects_index');
        }

        return $this->render('pointscollects/edit.html.twig', [
            'pointscollect' => $pointscollect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pointscollects_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pointscollects $pointscollect): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointscollect->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pointscollect);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pointscollects_index');
    }
}
