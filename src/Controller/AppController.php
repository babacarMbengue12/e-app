<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Wish;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
     
     /**
     * @Route("/stock", name="cart_validate")
     */
    public function cart_validate(CartRepository $cartRepository): Response
    {
        return $this->render('app/cart_validate.html.twig', [
            'carts' => $cartRepository->findBy(['ordered'=> true,"sended"=> false]),
        ]);
    }

     /**
     * @Route("/404", name="404")
     */
    public function Error404(): Response
    {
        return $this->render('app/404.html.twig');
    }

    /**
     * @Route("/", name="app")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy(['category' => null]);
        return $this->render('app/index.html.twig',[
            'categories'=> $categories
        ]);
    }

    /**
     * @Route("/categories/{id}", name="category_show")
     */
    public function category_show(Category $category,CategoryRepository $categoryRepository): Response
    {
        return $this->render('app/category.html.twig',[
            'category'=> $category,
            'categories'=> $categoryRepository->findBy(['category' => null])
        ]);
    }

    /**
     * @Route("/items/{id}", name="item_show")
     */
    public function item_show(Item $item): Response
    {
        return $this->render('app/item.html.twig',[
            'item'=> $item,
        ]);
    }

    /**
     * @Route("/member-select/{id}", name="member_select")
     */
    public function member_select(Item $item,MemberRepository $memberRepository,Request $request): Response
    {
        return $this->render('app/member.html.twig',[
            'item'=> $item,
            'members'=> $memberRepository->getOrSearch($request->query->get('search',null))
        ]);
    }
    /**
     * @Route("/cart-select/{itemId}/{memberId}", name="cart_select")
     */
    public function cart_select(MemberRepository $memberRepository,ItemRepository $itemRepository,Request $request,CartRepository $cartRepository): Response
    {
        $member = $memberRepository->find($request->get('memberId',0));
        $item = $itemRepository->find($request->get('itemId',0));
        $carts = $cartRepository->findByMember($member);
        return $this->render('app/cart.html.twig',[
            'item'=> $item,
            'member'=> $member,
            'carts'=> $carts
        ]);
    }
    /**
     * @Route("/cart-add/{itemId}/{memberId}/{cartId}", name="cart_add", methods={"POST"})
     */
    public function cart_add(MemberRepository $memberRepository,ItemRepository $itemRepository,Request $request,CartRepository $cartRepository): Response
    {
        $member = $memberRepository->find($request->get('memberId',0));
        $item = $itemRepository->find($request->get('itemId',0));
        $carts = $cartRepository->findByMember($member);
        $cart = $cartRepository->find($request->get('cartId'));
        
        $quantity = $request->get('quantity');
        if($quantity < 1) {
            return $this->render('app/cart.html.twig',[
                'item'=> $item,
                'member'=> $member,
                'carts'=> $carts,
                'message'=>"Quantity must be greater than 0"
            ]);
        }
        if($quantity > $item->getStock()){
            return $this->render('app/cart.html.twig',[
                'item'=> $item,
                'member'=> $member,
                'carts'=> $carts,
                'message'=>"insufficient quantity"
            ]);
        }

        $wish = new Wish();
        $wish->setCount($quantity);
        $wish->setMember($member);
        $wish->setCart($cart);
        $wish->setItem($item);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wish);
        
        $entityManager->flush();
         return $this->redirectToRoute('cart_index');
    }
}
