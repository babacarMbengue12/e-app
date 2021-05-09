<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Member;
use App\Form\CartType;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart_index", methods={"GET"})
     */
    public function index(CartRepository $cartRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $cartRepository->findBy(['ordered'=> false]),
        ]);
    }

     /**
     * @Route("/orders", name="orders", methods={"GET"})
     */
    public function orders(CartRepository $cartRepository): Response
    {
        return $this->render('cart/orders.html.twig', [
            'carts' => $cartRepository->findBy(['ordered'=> true]),
        ]);
    }

    /**
     * @Route("/orders/{id}", name="orders_member", methods={"GET"})
     */
    public function orders_member(Member $member,CartRepository $cartRepository): Response
    {
        return $this->render('cart/orders.html.twig', [
            'carts' => $cartRepository->findByMemberAndStatus($member,false),
        ]);
    }

     /**
     * @Route("/orders-old/{id}", name="orders_member_old", methods={"GET"})
     */
    public function orders_member_old(Member $member,CartRepository $cartRepository): Response
    {
        return $this->render('cart/orders.html.twig', [
            'carts' => $cartRepository->findByMemberAndStatus($member,true),
        ]);
    }

   

    /**
     * @Route("/new", name="cart_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cart = new Cart();
        $cart->setOrdered(false);
        $cart->setSended(false);
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/new.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_show", methods={"GET"})
     */
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cart $cart): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/order", name="cart_ordered", methods={"GET"})
     */
    public function cart_ordered(Cart $cart,CartRepository $cartRepository): Response
    {
        if($cart->getWishes()->count() > 0){
            $cart->setOrdered(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cart_index');
        }else{
            return $this->render('cart/index.html.twig', [
                'carts' => $cartRepository->findBy(['ordered'=> false]),
                'message'=> "empty cart"
            ]);
        }
    }

   

    /**
     * @Route("/{id}/send", name="cart_send", methods={"GET"})
     */
    public function cart_send(Cart $cart,CartRepository $cartRepository): Response
    {
            /**
             * @var Wish $wish
             **/
            foreach($cart->getWishes() as $wish){
                $item = $wish->getItem();
                if($wish->getCount() > $item->getStock()){
                    return $this->render('app/cart_validate.html.twig', [
                        'carts' => $cartRepository->findBy(['ordered'=> true,"sended"=> false]),
                        'message'=> "insufficient quantity"
                    ]);
                }
            }
            foreach($cart->getWishes() as $wish){
                $item = $wish->getItem();
                $item->setStock($item->getStock() - $wish->getCount() );
            }
            $cart->setSended(true);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cart_validate');
    }

    /**
     * @Route("/{id}", name="cart_delete", methods={"POST"})
     */
    public function delete(Request $request, Cart $cart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index');
    }
}
