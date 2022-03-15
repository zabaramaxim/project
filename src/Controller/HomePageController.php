<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Entity\User;
use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index( ItemCollectionRepository $collectionRepository, EntityManagerInterface $entityManager): Response
    {
        $collections = $collectionRepository->findBy([], [], 6);
        $countCollection = $collectionRepository->findCountCollection();

        return $this->render('home_page/index.html.twig', [ 'collections' => $collections, 'countCollection' => $countCollection

        ]);
    }

    #[Route('/home/post_view/{id}', name: 'post_view', requirements: ['id' => '\d+'])]
    public function showCollection(ItemCollectionRepository $collectionRepository, ItemRepository $itemRepository, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $username = $this->getUser();
        $collection = $collectionRepository->find($id);
        $items = $collection->getItems()->getValues();


        return $this->render('home_page/post_view.html.twig', [
            'collection' => $collection,
            'items'=>$items

        ]);
    }



}
