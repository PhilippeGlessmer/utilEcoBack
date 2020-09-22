<?php

namespace App\Controller;

use App\Entity\Proccess;
use App\Entity\Types;
use App\Form\TypesType;
use App\Repository\ProccessRepository;
use App\Repository\TypesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Materiels;
use App\Entity\TestsProcess;
use App\Form\DistributeurMaterielsType;
use App\Form\MaterielsType;
use App\Form\ParticulierMaterielsType;
use App\Repository\MaterielsRepository;
use App\Repository\TestsProcessRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 * @Route("/backoffice/types")
 */
class TypesController extends AbstractController
{
    /**
     * @Route("/", name="types_index", methods={"GET"})
     */
    public function index(TypesRepository $typesRepository): Response
    {
        return $this->render('types/index.html.twig', [
            'types' => $typesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="types_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $type = new Types();
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('types_index');
        }

        return $this->render('types/new.html.twig', [
            'type' => $type,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="types_show", methods={"GET", "POST"})
     */
    public function show(Types $type, ProccessRepository $proccessRepository, Request $request): Response
    {
        $process = $proccessRepository->findBy(['type' => $type->getId()], array('ordre' => 'ASC'));
        if($process) {
            $test1 = $proccessRepository->findBy([ 'type' => $type->getId() ], array( 'ordre' => 'DESC' ), 1);
            $stepSuivant = $test1[0]->getOrdre() + 1;
        }else{
            $stepSuivant = 1;
        }
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('action', TextareaType::class, [
                'label' => 'Action a exécuter',
            ])
            ->add(
                'resultNegatif', ChoiceType::class, [
                    'label' => 'Résultat négatif',
                    'choices' => ['' => null , 'A donner' => -2, 'A jeter' => -1],
                    'required' => true,
                ]
            )
            // ->add('resultPositif')
            ->add('ordre', IntegerType::class,[
                'required'   => false,
                'data' => $stepSuivant,

            ])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $process = New Proccess();
            $process->setType($type);
            $process->setAction($form->get('action')->getData());
            $process->setResultNegatif($form->get('resultNegatif')->getData());
            $process->setOrdre($form->get('ordre')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($process);
            $entityManager->flush();
            //return $this->redirectToRoute($request->server->get('HTTP_REFERER'));
            return $this->redirectToRoute('types_show', ['id' => $type->getId()]);
        }
        return $this->render('types/show.html.twig', [
            'type' => $type,
            'process' => $process,
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/{id}/edit", name="types_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Types $type): Response
    {
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('types_index');
        }

        return $this->render('types/edit.html.twig', [
            'type' => $type,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="types_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Types $type): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('types_index');
    }
}
