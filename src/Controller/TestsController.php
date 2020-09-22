<?php

namespace App\Controller;

use App\Entity\Materiels;
use App\Entity\TestsProcess;
use App\Form\DistributeurMaterielsType;
use App\Form\MaterielsType;
use App\Form\ParticulierMaterielsType;
use App\Repository\MaterielsRepository;
use App\Repository\ProccessRepository;
use App\Repository\TestsProcessRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/backoffice/tests")
 */

class TestsController extends AbstractController
{
    private $router;
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route("/tests", name="tests")
     */
    public function index()
    {
        return $this->render('tests/index.html.twig', [
            'controller_name' => 'TestsController',
        ]);
    }

    /**
     * @Route("/identification-provenance", name="identification-provenance")
     */
    public function identificationProvenance(Request $request, Session $session):Response
    {
        $session->set('id', null);
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add(
                'Question1', ChoiceType::class, [
                    'choices' => ['Choisissez' => '', 'Fournisseur' => 'fournisseur', 'Particulier' => 'particulier', 'Distributeur' => 'distributeur'],
                    'multiple'=>false,
                    'expanded'=>true,
                    'required'   => true,
                ]
            )
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->get('Question1')->getData();
            if($action == 'fournisseur') { // Si fournisseur
                $this->addFlash('Success', 'L\'appareil provient d\'un fournisseur');
                return $this->redirect('new-appareil-lot');
            }elseif($action == 'particulier'){ // Si Particulier
                $this->addFlash('Success', 'L\'appareil provient d\'un particulier');
                return $this->redirect('new-appareil-particulier');
            }elseif($action == 'distributeur'){ // Si distributeur
                $this->addFlash('Success', 'L\'appareil provient d\'un particulier');
                return $this->redirect('new-appareil-distributeur');
        }else{ // Si form non valide
                $this->addFlash('Error', 'Vous devez choisir la provenance de l\'appareil à tester');
            }
        }
        return $this->render('tests/firststep.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'FirstSept',
        ]);
    }

    /**
     * @Route("/new-appareil-lot", name="new-appareil-lot", methods={"GET","POST"})
     */
    public function new(Request $request, Session $session): Response
    {
        // Provenance Fournisseur
        $materiel = new Materiels();
        $form = $this->createForm(MaterielsType::class, $materiel); // Builder MarterielType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();
            $lastId = $materiel->getId();
            $session->set('IdMateriel', $lastId); // Crée une session ID
            return $this->redirectToRoute('identification'); // redirige vers page récapitulatif
        }

        return $this->render('tests/new-appareil-lot.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new-appareil-particulier", name="new-appareil-particulier", methods={"GET","POST"})
     */
    public function newAppareilParticulier(Request $request, Session $session): Response
    {
        // Provenance Particulier
        $materiel = new Materiels();
        $form = $this->createForm(ParticulierMaterielsType::class, $materiel); // Builder ParticulierMaterielsType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            $lastId = $materiel->getId();
            $session->set('IdMateriel', $lastId); // Crée une session ID
            return $this->redirectToRoute('identification');  // redirige vers page récapitulatif
        }

        return $this->render('tests/new-appareil-particulier.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new-appareil-distributeur", name="new-appareil-distributeur", methods={"GET","POST"})
     */
    public function newAppareilDistributeur(Request $request, Session $session): Response
    {
        // Provenance Distributeur
        $materiel = new Materiels();
        $form = $this->createForm(DistributeurMaterielsType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

            $lastId = $materiel->getId();
            $session->set('IdMateriel', $lastId); // Crée une session ID
            return $this->redirectToRoute('identification');  // redirige vers page récapitulatif
        }

        return $this->render('tests/new-appareil-distributeur.html.twig', [
            'materiel' => $materiel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/identification/", name="identification")
     */
    public function identificationMateriel(MaterielsRepository $materielsRepository,ProccessRepository $proccessRepository,TestsProcessRepository $testsProcessRepository, Session $session): Response
    {

        $id =  $session->get('IdMateriel'); // Recupère session IdMateriel
        $materiel = $materielsRepository->find($id); // Recupère object Matériel de l'id de la session
        $totalprocess = $proccessRepository->findBy(['type' => $materiel->getModele()->getType()]); // récupère les procédures par types
        $nbProcess = count($totalprocess) - 2; // Compte les procédures et déduit le nombre de procédure négative. TODO créer fonction pour compter les procédure négative.
        $tests = $testsProcessRepository->findBy(['materiel' =>$materiel->getId()]);  // Récupere les tests faite par id de cette appareil
        $test = count($tests); // compte le nombre de test
        if($test < $nbProcess){ // Si nb de test est inférieur aux nombres de procédure
            $recuperationTests = $testsProcessRepository->findBy(['materiel' =>$materiel->getId()], ['proccess' => 'DESC'], 1); // Récupère la derniere entré des test par du Matériel
            // la condition permet de reprendre un test arrété
            if($test > 0){ // si il y a des tests
                $idLastTest = $recuperationTests[0]->getProccess()->getOrdre();
                $reprise = true; // Affiche card reprendre test
            }else{ // s'il n'y en a pas. donc 0
                $idLastTest = 0;
                $reprise = false; // Ne pas affiche card reprendre test
            }
            $session->set('step', $idLastTest+1); // Enregistre l'id Step.

        }elseif($test == $nbProcess){ // si le nombre de test est = au nombre de procédure tu va sur résultat
            return $this->redirectToRoute('resultat');
        }else{
            $reprise = false;
        }
        return $this->render('tests/identification.html.twig', [
            'materiel' => $materiel,
            'tests' =>$tests,
            'nbProccess' => $nbProcess,
            'nbTest' => $test,
            'reprise' => $reprise,
        ]);
    }
    /**
     * @Route("/secondstep/", name="secondstep")
     */
    public function secondstep(Request $request,MaterielsRepository $materielsRepository, Session $session, ProccessRepository $proccessRepository): Response
    {
        $session->set('resultNegatif', null);
        // Identification Materiel
        $idMateriel =  $session->get('id');
        $materiel = $materielsRepository->find($idMateriel);
        // step
        $ordre = $session->get('step');
        // Nombre de process
        $totalprocess = $proccessRepository->findBy(['type' => $materiel->getModele()->getType()]);
        //totalprocess = $proccessRepository->findBy(['type' => $materiel->getModele()->getType(), 'ordre' => ]); // TODO j'ai oublié
        $nbProcess = count($totalprocess) - 2; // Compte les procédures et déduit le nombre de procédure négative. TODO créer fonction pour compter les procédure négative.

        $form = $this->createFormBuilder() // création du formulaire
            ->add(
                'Reponse', ChoiceType::class, [
                    'choices' => ['Negatif' => '0', 'Positif' => '1'],
                    'multiple'=>false,
                    'empty_data' => null,
                    'expanded'=>true,
                    'required'   => true,
                    'label_attr'=>[
                        'class'=>'radio-inline'
                    ]
                ]
            )
            ->add('token', HiddenType::class, [
                'data' => 'abcdef'
                ]
            )
            ->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary w-100'],
            ])
            ->getForm();
            ;
        $form->handleRequest($request);

        $ordre = $session->get('step'); // Récupere le numéro test/étape
        $process = $proccessRepository->findBy(['type' => $materiel->getModele()->getType(), 'ordre' =>$ordre]); // Récupère process TYPE et ETAPE(ordre)
        if ($form->isSubmitted()) {
            $action = $form->get('Reponse')->getData();
            if($action == 1){  // Si Positif

                $init = $session->get('step');
                ++$init;
                $session->set('step', $init); // Rajoute 1 > passe a la procédure suivante

                $test = new TestsProcess(); // Nouvelle objet
                $test->setUser($this->getUser()); // Qui
                $test->setMateriel($materiel); // Quoi
                $test->setProccess($process[0]); // Quel Procédure
                $test->setResultat(1); // Résultat
                $test->setCreateAt(new \DateTime()); // Quand
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($test);
                $entityManager->flush(); // Enregistrement
                if($nbProcess == $ordre){ // Nobre de procédure et egale à n° de procédure
                    return $this->redirectToRoute('resultat'); // Fini
                }
                else{
                    return $this->redirectToRoute('secondstep'); // Recharge la page
                }
            }else{
                $test = new TestsProcess();
                $test->setMateriel($materiel); // Quoi
                $test->setProccess($process[0]); // Quel Procédure
                $test->setResultat('0'); // résultat
                $test->setCreateAt(new \DateTime()); // Quand
                $test->setUser($this->getUser()); // Qui
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($test);
                $entityManager->flush(); // Enregistrement

                $resultNegatif = $process[0]->getResultNegatif();
                $session->set('resultNegatif', $resultNegatif); // Crée session avec le test lequel il a echoué

                return $this->redirectToRoute('resultat'); // Affiche résultat
            }

        }else{
            // Reprendre Test ou Nouveau test
            if($session->get('step') ){
                $ordre = $session->get('step');
            }
            else{
                $init = 1;
                $session->set('step', $init);
            }
        }
        $ordre = $session->get('step');
        $process = $proccessRepository->findBy(['type' => $materiel->getModele()->getType(), 'ordre' =>$ordre]); // Affiche la procédure demandé Session step(ordre)
        return $this->render('tests/secondstep.html.twig', [
            'form' => $form->createView(),
            'materiel' => $materiel,
            'process' => $process,
            'nbproccess' => $nbProcess,
            'step' => $ordre,
        ]);
    }
    /**
     * @Route("/resultat/", name="resultat")
     */
    public function resultat(Request $request,MaterielsRepository $materielsRepository, ProccessRepository $proccessRepository, TestsProcessRepository $testsProcessRepository, Session $session): Response
    {
        //identification du Materiel
        $id =  $session->get('id');
        $materiel = $materielsRepository->find($id);
        // Si négatif
        $resultNegatif = $session->get('resultNegatif');
        $defaultData = ['message' => ''];
        if($resultNegatif){ // Négatif
            $process = $proccessRepository->findBy(['type' => $materiel->getModele()->getType(), 'ordre' =>$resultNegatif]);
            $process = $process[0];
            $formResultat = $this->createFormBuilder($defaultData)
                ->add('etat', HiddenType::class, [
                    'data' => 3,
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Valider le résultat',
                    'row_attr' => ['class' => 'text-center'],

                ])
                ->getForm()
            ;

        }else{ // Si Positif
            $process = null;
            $formResultat = $this->createFormBuilder($defaultData)
                ->add('etat', HiddenType::class, [
                    'data' => 1,
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Valider le résultat',
                    'row_attr' => ['class' => 'text-center'],

                ])
                ->getForm()
            ;
        }
        // enregistrement de l'état
        $formResultat->handleRequest($request);
        if ($formResultat->isSubmitted() && $formResultat->isValid()) {

            $etat = $formResultat->get('etat')->getData();
            $materiel->setEtat($etat);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($materiel);
            $entityManager->flush();

        }
        $testsMateriels = $testsProcessRepository->findBy(['materiel' => $materiel]);



        return $this->render('tests/resultat.html.twig', [
            'resultat' =>$resultNegatif,
            'proccess' => $process,
            'etat' => $materiel->getEtat(),
            'formpositif' => $formResultat->createView(),

            'materiel' => $materiel,
            'testmateriel' => $testsMateriels,
        ]);
    }
}
