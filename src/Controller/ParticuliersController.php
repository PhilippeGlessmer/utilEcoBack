<?php

namespace App\Controller;

use App\Entity\Particuliers;
use App\Form\ParticuliersType;
use App\Repository\ParticuliersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/particuliers")
 */
class ParticuliersController extends AbstractController
{
    /**
     * @Route("/", name="particuliers_index", methods={"GET"})
     */
    public function index(ParticuliersRepository $particuliersRepository): Response
    {
        return $this->render('particuliers/index.html.twig', [
            'particuliers' => $particuliersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="particuliers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $particulier = new Particuliers();
        $form = $this->createForm(ParticuliersType::class, $particulier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($particulier);
            $entityManager->flush();

            return $this->redirectToRoute('particuliers_index');
        }

        return $this->render('particuliers/new.html.twig', [
            'particulier' => $particulier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="particuliers_show", methods={"GET"})
     */
    public function show(Particuliers $particulier): Response
    {
        return $this->render('particuliers/show.html.twig', [
            'particulier' => $particulier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="particuliers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Particuliers $particulier): Response
    {
        $form = $this->createForm(ParticuliersType::class, $particulier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('particuliers_index');
        }

        return $this->render('particuliers/edit.html.twig', [
            'particulier' => $particulier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="particuliers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Particuliers $particulier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$particulier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($particulier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('particuliers_index');
    }
}
