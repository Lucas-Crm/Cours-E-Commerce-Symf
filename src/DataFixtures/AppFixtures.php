<?php

namespace App\DataFixtures;

use App\Entity\Gender;
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
        // TODO Mettre la date de naissance

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
        for($i = 0; $i < 3; $i++){
            $gender = (new Gender)
                ->setName('gender' . $i)
                ->setEnable(true);

            $manager->persist($gender);
        }



        $manager->flush();
    }
}
