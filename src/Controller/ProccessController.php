<?php

namespace App\Controller;

use App\Entity\Proccess;
use App\Form\ProccessType;
use App\Repository\ProccessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/proccess")
 */
class ProccessController extends AbstractController
{
    /**
     * @Route("/", name="proccess_index", methods={"GET"})
     */
    public function index(ProccessRepository $proccessRepository): Response
    {
        return $this->render('proccess/index.html.twig', [
            'proccesses' => $proccessRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="proccess_new", methods={"GET","POST"})
     */
    public function new(Request $request, Session $session): Response
    {

        $proccess = new Proccess();
        $form = $this->createForm(ProccessType::class, $proccess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proccess);
            $entityManager->flush();
            $init = $session->get('step');
            ++$init;
            $session->set('ordre', $init);

            return $this->redirectToRoute('proccess_index');
        }else{
            $init = 1;
            $session->set('ordre', $init);
        }
        if($session->get('ordre')){
            $step = $session->get('ordre');
        }else{
            $step = 1;
        }

        return $this->render('proccess/new.html.twig', [
            'proccess' => $proccess,
            'form' => $form->createView(),
            'step' => $step,

        ]);
    }

    /**
     * @Route("/{id}", name="proccess_show", methods={"GET"})
     */
    public function show(Proccess $proccess): Response
    {
        return $this->render('proccess/show.html.twig', [
            'proccess' => $proccess,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proccess_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Proccess $proccess): Response
    {
        $form = $this->createForm(ProccessType::class, $proccess);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            //return $this->redirectToRoute('types_show', ['id' => $proccess->getType()]);
            return $this->redirectToRoute('proccess_index');
        }

        return $this->render('proccess/edit.html.twig', [
            'proccess' => $proccess,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proccess_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Proccess $proccess): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proccess->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proccess);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proccess_index');
    }
}
