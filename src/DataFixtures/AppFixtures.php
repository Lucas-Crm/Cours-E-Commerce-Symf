<?php

namespace App\DataFixtures;

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
                ;

            $manager->persist($user);
        };

        $manager->flush();
    }
}
