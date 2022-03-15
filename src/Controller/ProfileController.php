<?php

namespace App\Controller;
//require_once App\Vendor\autoload.php;

use App\Entity\Item;
use App\Entity\ItemCollection;
use App\Form\ItemCollectionType;
use App\Form\ItemType;
use App\Repository\ItemCollectionRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile', )]
    public function index(ItemCollectionRepository $collectionRepository, ItemRepository $itemRepository, EntityManagerInterface $entityManager): Response
    {

        $username = $this->getUser()->getUsername();
        $collections = $collectionRepository->findByUsername($username);

        return $this->render('profile/index.html.twig', [
            'collections' => $collections, 'username' => $username,
        ]);
    }

    #[Route('/delete/post/{id}', name: 'delete', requirements: ['id' => '\d+'] )]
    public function delete_collection(ItemCollectionRepository $collectionRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $username = $this->getUser()->getUsername();
        $collection = $collectionRepository->find($id);
        $entityManager->remove($collection);
        $entityManager->flush();

        return $this->redirectToRoute('profile');


    }



    #[Route('/profile/post/{id}', name: 'post', requirements: ['id' => '\d+'])]
    public function showCollection(ItemCollectionRepository $collectionRepository, ItemRepository $itemRepository, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $username = $this->getUser();
        $collection = $collectionRepository->find($id);
        $items = $collection->getItems()->getValues();


        return $this->render('profile/post.html.twig', [ 'collection' => $collection, 'items'=>$items

        ]);
    }



    #[Route('/profile/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $url = '';
        $clientId = $this->getParameter('dropbox_client');
        $clientSecret = $this->getParameter('dropbox_secret');
        $accessToken = $this->getParameter('dropbox_token');
        $app = new DropboxApp($clientId, $clientSecret, $accessToken);
        $dropbox = new Dropbox($app);

        if(!empty($_FILES)){

            $supportTypes = ["image/jpeg", "image/png"];
            $name = uniqid();
            $path = $_FILES['file']['tmp_name'];
            $ext = explode(".", $_FILES['file']['name']);
            $ext = end($ext);
            if (!in_array($_FILES['file']['type'], $supportTypes)){
                $this->addFlash('error', 'Choose JPG or PNG file');
                return $this->redirectToRoute('create');

            }
            $nameDropbox = "/" . $name . "." . $ext;
            $mode = DropboxFile::MODE_READ;
            $dropboxFile = new DropboxFile($path, $mode);

            try {
                $uploadedFile = $dropbox->upload($dropboxFile, $nameDropbox, ['autorename'=>true]);
            }catch (\exception $exception){
                print_r($exception);
            }
            $response = $dropbox->postToAPI("/sharing/create_shared_link_with_settings" , ["path"=>$nameDropbox]);
            $data = $response -> getDecodedBody();
            $url = $data['url'];
            $url = str_replace('dl=0', 'raw=1', $url );

        }

        $collection = new ItemCollection();
        $collection->setUser($this->getUser());
        $collection->setImage($url);
        $form = $this->createForm(ItemCollectionType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($collection);
            $entityManager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/create.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form->createView(), 'collection' => $collection,
        ]);
    }

    #[Route('/profile/add_item/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function addItem(ItemCollectionRepository $collectionRepository, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $collection = $collectionRepository->find($id);
        $item = new Item();
        $item->setCreatedAt(new \DateTime('today'));

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $collection->addItem($item);
            $entityManager->persist($collection);
            $entityManager->persist($item);
            $entityManager->flush();
            return $this->redirectToRoute('post', ['id'=>$id]);
        }



        return $this->render('profile/add_item.html.twig', [
            'form' => $form->createView(), //'collection' => $collection,
        ]);
    }

//    #[Route('/profile/delete/{id}', name: 'add', requirements: ['id' => '\d+'])]
//    public function deleteItem(ItemCollectionRepository $collectionRepository, Request $request, int $id, EntityManagerInterface $entityManager): Response
//    {
//        $collection = $collectionRepository->find($id);
//        $collection->removeItem()
//
//
//        $entityManager->persist($collection);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('post', ['id'=>$id]);
//    }


}
