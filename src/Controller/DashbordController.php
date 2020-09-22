<?php

namespace App\Controller;

use App\Repository\MaterielsRepository;
use App\Repository\ModelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice")
 */

class DashbordController extends AbstractController
{
    /**
     * @Route("/dashbord", name="dashbord")
     */
    public function index(MaterielsRepository $materielsRepository, ModelsRepository $modelsRepository): Response
    {

        $materiel = $materielsRepository->findBy(['etat' => !null]);
        $materieEnCours = $materielsRepository->findBy(['etat' => null]);
        $nbEncours = count($materieEnCours);
        $materieAVendre = $materielsRepository->findBy(['etat' => 1]);
        $nbAVendre = count($materieAVendre);
        $materieADonner = $materielsRepository->findBy(['etat' => 2]);
        $nbADonner = count($materieADonner);
        $materieJeter = $materielsRepository->findBy(['etat' => 3]);
        $nbjeter = count($materieJeter);
        $lastModule = $modelsRepository->findBy(array(), array('id' => 'desc'),8,0);
        //$lastModule = $modelsRepository->findAll();
        return $this->render('dashbord/index.html.twig', [
            'materiels' => $materiel,
            'nbencours' => $nbEncours,
            'nbavendre' => $nbAVendre,
            'nbdonner'=>$nbADonner,
            'nbjeter'=>$nbjeter,
            'lastModels'=>$lastModule,
            'testEnCour'=>$materieEnCours,
            'controller_name' => 'DashbordController',
        ]);
    }
}
