<?php

namespace App\Controller;

use App\Entity\Fournisseurs;
use App\Form\FournisseursType;
use App\Repository\FournisseursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/fournisseurs")
 */
class FournisseursController extends AbstractController
{
    /**
     * @Route("/", name="fournisseurs_index", methods={"GET"})
     */
    public function index(FournisseursRepository $fournisseursRepository): Response
    {
        return $this->render('fournisseurs/index.html.twig', [
            'fournisseurs' => $fournisseursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fournisseurs_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $fournisseur = new Fournisseurs();
        $form = $this->createForm(FournisseursType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('fournisseurs_index');
        }

        return $this->render('fournisseurs/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fournisseurs_show", methods={"GET"})
     */
    public function show(Fournisseurs $fournisseur): Response
    {

        $startAddress = '2 avenue des Fontaines, 64680, Ogeu-les-bains';

        $LatLngDepot = ['lat'=> '43.316', 'lng' => '-0.322'];
        $endAddress = $fournisseur->getAdresse().' '.$fournisseur->getCodePostale().' '.$fournisseur->getVille();
        function strToNoAccent($var) {
            $var = str_replace(
                array(
                    ' ', ',',
                    'à', 'â', 'ä', 'á', 'ã', 'å',
                    'î', 'ï', 'ì', 'í',
                    'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
                    'ù', 'û', 'ü', 'ú',
                    'é', 'è', 'ê', 'ë',
                    'ç', 'ÿ', 'ñ',
                    'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
                    'Î', 'Ï', 'Ì', 'Í',
                    'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø',
                    'Ù', 'Û', 'Ü', 'Ú',
                    'É', 'È', 'Ê', 'Ë',
                    'Ç', 'Ÿ', 'Ñ',
                ),
                array(
                    '+','',
                    'a', 'a', 'a', 'a', 'a', 'a',
                    'i', 'i', 'i', 'i',
                    'o', 'o', 'o', 'o', 'o', 'o',
                    'u', 'u', 'u', 'u',
                    'e', 'e', 'e', 'e',
                    'c', 'y', 'n',
                    'A', 'A', 'A', 'A', 'A', 'A',
                    'I', 'I', 'I', 'I',
                    'O', 'O', 'O', 'O', 'O', 'O',
                    'U', 'U', 'U', 'U',
                    'E', 'E', 'E', 'E',
                    'C', 'Y', 'N',
                ),$var);
            return $var;
        }
        return $this->render('fournisseurs/show.html.twig', [
            'fournisseur' => $fournisseur,
            'startAddress' => strToNoAccent($startAddress),
            'endAddress' => strToNoAccent($endAddress),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fournisseurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fournisseurs $fournisseur): Response
    {
        $form = $this->createForm(FournisseursType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fournisseurs_index');
        }

        return $this->render('fournisseurs/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fournisseurs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Fournisseurs $fournisseur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fournisseurs_index');
    }
}
