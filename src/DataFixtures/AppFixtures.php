<?php

namespace App\DataFixtures;

use App\Entity\Delivery;
use App\Entity\Gender;
use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        // Fixture Users
        $user = new User();
        $user->setEmail('rb@admin.fr')
            ->setPassword($this->hasher->hashPassword($user, 'rmrf1234!'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstName('Robert')
            ->setLastName('Mitchoum')
            ->setTelephone('0123456789');

        $manager->persist($user);

        for ($i = 0; $i < 10; $i++){
            $user = (new User)
                ->setEmail('user-' . $i . '@fix.fr')
                ->setPassword($this->hasher->hashPassword($user, 'Test1234!'))
                ->setFirstName('user' . $i)
                ->setLastName('test')
                ->setRoles(["ROLE_USER"])
                ;

            $manager->persist($user);
        };


        //Fixture Gender
        $genderName = ['Homme','Femme','Enfant'];
        foreach ($genderName as $name){
            $gender = (new Gender)
                ->setName($name)
                ->setEnable(true);
            $manager->persist($gender);
        }

        //Fixture Model
        $modelName = ['Chaussure', 'Sandale'];
        foreach ($modelName as $name){
            $model = (new Model)
                ->setName($name)
                ->setEnable(true)
                ;
            $manager->persist($model);
        }

        //Fixture Marque
        $marquesName = ['Nike', 'Adidas', 'New Balance', 'Levis'];
        foreach ($marquesName as $name){
            $marque = (new Marque)
                ->setName($name)
                ->setEnable(true);

            $manager->persist($marque);
        }



        //Fixture Delivery
        $deliveryName = ['5 - 1O jours ouvree', ' 1 jour Express', '3 jour ouvree'];
        foreach ($deliveryName as $name){
            $delivery = (new Delivery)
                ->setName($name)
                ->setDescription('Description ' . $name)
                ->setPrice(10 + $i)
                ->setEnable(true);

            $manager->persist($delivery);

        }

        $manager->flush();
    }
}
