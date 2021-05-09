<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Family;
use App\Entity\Item;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $categories = [];
        $allCategories = [];
        for($i = 0;$i < 5;$i++){
            $category = new Category();
            $category->setTitle($faker->name);
            $manager->persist($category);
            $categories[] = $category;
            $allCategories[] = $category;
        }
        for($i = 0;$i < 20;$i++){
            $category = new Category();
            $category->setTitle($faker->name);
            $category->setCategory($categories[$faker->numberBetween(0,4)]);
            $manager->persist($category);
            $allCategories[] = $category;
        }
        for($i  =0;$i < 30;$i++){
            $item = new Item();
            $item->setName($faker->name);
            $item->setDescription($faker->text(255));
            $item->setStock($faker->numberBetween(100,1000));
            $item->setUnitPrice($faker->numberBetween(5,10000));
            $cats = $faker->randomElements($allCategories,10);
            foreach($cats as $c){
                $item->addCategory($c);
            }
            $manager->persist($item);
        }

        $families = [];
        for($i = 0;$i < 10;$i++){
            $family = new Family();
            $family->setName($faker->name);
            $manager->persist($family);
            $families[] = $family;
        }
        for($i = 0;$i < 20;$i++){
            $member = new Member();
            $member->setName($faker->name);
            $member->setPhone($faker->phoneNumber);
            $member->setEmail($faker->email);
            $cats = $faker->randomElements($families,5);
            foreach($cats as $c){
                $member->addFamily($c);
            }
            $manager->persist($member);
        }
        for($i = 0;$i < 25;$i++){
            $cart = new Cart();
            $cart->setTitle($faker->name);
            $cart->setOrdered(false);
            $cart->setSended(false);
            $cart->setFamily($families[$faker->numberBetween(0,9)]);
            $manager->persist($cart);
        }
        $manager->flush();
    }
}
