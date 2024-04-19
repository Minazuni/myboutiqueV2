<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{

    private $passwordHasher;
  

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
      
    }

    public function load(ObjectManager $manager): void
    {

        $admin = new User();
        $admin->setFirstName('Eric')
            ->setLastName('Devolder')
            ->setEmail('admin@admin.fr')
            ->setPassword($this->passwordHasher->hashPassword(
                $admin,
               'a'
            ))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);    

        $faker = Factory::create('fr_FR');   

        for ($i=1; $i <=10 ; $i++) { 
            
            $user = new User();
            $user->setFirstName($faker->firstName())
            ->setLastName($faker->lastName())
            ->setEmail($faker->email())
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
               'a'
            ));

         $manager->persist($user);   

        }

        $manager->flush();
    }
}
