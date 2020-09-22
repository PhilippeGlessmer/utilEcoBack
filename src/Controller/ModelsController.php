<?php

namespace App\Controller;

use App\Entity\Models;
use App\Form\ModelsType;
use App\Repository\ModelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/models")
 */
class ModelsController extends AbstractController
{
    /**
     * @Route("/", name="models_index", methods={"GET"})
     */
    public function index(ModelsRepository $modelsRepository): Response
    {
        return $this->render('models/index.html.twig', [
            'models' => $modelsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="models_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $model = new Models();
        $form = $this->createForm(ModelsType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('models_index');
        }

        return $this->render('models/new.html.twig', [
            'model' => $model,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="models_show", methods={"GET"})
     */
    public function show(Models $model): Response
    {
        return $this->render('models/show.html.twig', [
            'model' => $model,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="models_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Models $model): Response
    {
        $form = $this->createForm(ModelsType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('models_index');
        }

        return $this->render('models/edit.html.twig', [
            'model' => $model,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="models_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Models $model): Response
    {
        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($model);
            $entityManager->flush();
        }

        return $this->redirectToRoute('models_index');
    }
}
