<?php

namespace App\Controller;

use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteItemController extends AbstractController
{
    #[Route('/delete_item/{item_id}', name: 'delete_item', requirements: ['item_id'=>'\d+'])]
    public function index(ItemCollectionRepository $collectionRepository, ItemRepository $itemRepository, int $item_id, EntityManagerInterface $entityManager): Response
    {
        $item = $itemRepository->findItemAndCollection($item_id);
        $collection_id = $item->getCollection()->getId();
        $entityManager->remove($item);
        $entityManager->flush();
        return $this->redirectToRoute('post', ['id'=>$collection_id]);

    }
}
