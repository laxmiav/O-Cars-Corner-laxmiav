<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Service\WishlistManager;

class WishlistController extends AbstractController
{
    /**
     * @Route("/wishlist", name="wishlist_list")
     */
       
 public function list()
 {
     return $this->render('wishlist/list.html.twig');
 }

 /**
  *
  * 
  * @Route("/wishlist/manager/{id}", name="wishlist_manager", requirements={"id"="\d+"})
  */
 public function wishlist(Car $Car,WishlistManager $wishlistsManager)
 {
     $action = $wishlistsManager->toggle($Car);
     
     if ($action == 'add') {
         $this->addFlash('success', $Car->getModel() . '  Added to wishlist');
     } else {
         $this->addFlash('success', $Car->getModel() . ' Removed from wishlist');
     }

     // On redirige vers la home
     return $this->redirectToRoute('wishlist_list');
 }

 /**
  * 
  * 
  * @Route("/wishlist/manager/purge", name="wishlist_purge")
  */
 public function purgewishlists(WishlistManager $wishlistsManager)
 {
     // On vide l'attribut de session concerné
     if ($wishlistsManager->empty()) {
         $this->addFlash('success', 'Wishlist supprimés');
     } else {
         $this->addFlash('danger', 'Cette fonctionnalité est désactivée');
     }

     // On redirige vers la liste
     return $this->redirectToRoute('wishlist_list');
 }
   
}







   

