<?php

namespace App\Controller;

use App\Entity\Distributeurs;
use App\Form\DistributeursType;
use App\Repository\DistributeursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/distributeurs")
 */
class DistributeursController extends AbstractController
{
    /**
     * @Route("/", name="distributeurs_index", methods={"GET"})
     */
    public function index(DistributeursRepository $distributeursRepository): Response
    {
        return $this->render('distributeurs/index.html.twig', [
            'distributeurs' => $distributeursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="distributeurs_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $distributeur = new Distributeurs();
        $form = $this->createForm(DistributeursType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($distributeur);
            $entityManager->flush();

            return $this->redirectToRoute('distributeurs_index');
        }

        return $this->render('distributeurs/new.html.twig', [
            'distributeur' => $distributeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="distributeurs_show", methods={"GET"})
     */
    public function show(Distributeurs $distributeur): Response
    {
        return $this->render('distributeurs/show.html.twig', [
            'distributeur' => $distributeur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="distributeurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Distributeurs $distributeur): Response
    {
        $form = $this->createForm(DistributeursType::class, $distributeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('distributeurs_index');
        }

        return $this->render('distributeurs/edit.html.twig', [
            'distributeur' => $distributeur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="distributeurs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Distributeurs $distributeur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$distributeur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($distributeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('distributeurs_index');
    }
}
