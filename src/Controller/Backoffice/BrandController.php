<?php

namespace App\Controller\Backoffice;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Ferrandini\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/brand")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_app_brand_index", methods={"GET"})
     */
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('backoffice/brand/index.html.twig', [
            'brands' => $brandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_app_brand_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BrandRepository $brandRepository,ManagerRegistry $doctrine ): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandRepository->add($brand);

            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $brand->setImage($newFilename);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($brand);
           
            $this->addFlash('success', 'Thanks for registering your car with us');
            // dire à doctrine d'exécuter les requêtes
            $entityManager->flush();
            




            return $this->redirectToRoute('backoffice_app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_app_brand_show", methods={"GET"})
     */
    public function show(Brand $brand): Response
    {
        return $this->render('backoffice/brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_app_brand_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Brand $brand, BrandRepository $brandRepository,ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandRepository->add($brand);

            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $brand->setImage($newFilename);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($brand);
           
            $this->addFlash('success', 'Brand is well registered');
            // dire à doctrine d'exécuter les requêtes
            $entityManager->flush();
            



            return $this->redirectToRoute('backoffice_app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_app_brand_delete", methods={"POST"})
     */
    public function delete(Request $request, Brand $brand, BrandRepository $brandRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $brandRepository->remove($brand);
        }

        return $this->redirectToRoute('backoffice_app_brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
