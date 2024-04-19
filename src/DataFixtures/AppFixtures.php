<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

     
        
        for ($i=1; $i <=10 ; $i++) { 

            $categorie= new Category();
            $categorie->setName('CAT '.$i);

            $manager->persist($categorie);

            $this->addReference('categorie_'.$i,$categorie);

        }

        $manager->flush();
    }
}
