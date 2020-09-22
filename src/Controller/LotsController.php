<?php

namespace App\Controller;

use App\Entity\Lots;
use App\Form\LotsType;
use App\Repository\LotsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\NotificationEmail;

/**
 * @Route("/backoffice/lots")
 */
class LotsController extends AbstractController
{
    /**
     * @Route("/", name="lots_index", methods={"GET"})
     */
    public function index(LotsRepository $lotsRepository): Response
    {
        return $this->render('lots/index.html.twig', [
            'lots' => $lotsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lots_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lot = new Lots();
        $lot->setDateDebut(new \DateTime());
        $form = $this->createForm(LotsType::class, $lot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lot);
            $entityManager->flush();
            return $this->redirectToRoute('lots_index');
        }

        return $this->render('lots/new.html.twig', [
            'lot' => $lot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lots_show", methods={"GET"})
     */
    public function show(Lots $lot): Response
    {


        return $this->render('lots/show.html.twig', [
            'lot' => $lot,

        ]);
    }

    /**
     * @Route("/etiquette/{id}", name="lots_show_etiquette", methods={"GET"})
     */
    public function etiquette(Lots $lot): Response
    {


        return $this->render('lots/etiquette.html.twig', [
            'lot' => $lot,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="lots_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lots $lot): Response
    {
        $form = $this->createForm(LotsType::class, $lot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lots_index');
        }

        return $this->render('lots/edit.html.twig', [
            'lot' => $lot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lots_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lots $lot): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lots_index');
    }
}
