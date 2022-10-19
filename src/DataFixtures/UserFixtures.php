<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function getDependencies()
    {
        return [CustomerFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $usersData=[
            ['id'=>1, 'email'=>'jeanlouis@hotmail.fr', 'name'=>'Jean louis', 'password'=>'jeanlouis12345', 'customer_id'=>1],
            ['id'=>2, 'email'=>'kevin@hotmail.fr', 'name'=>'Kevin', 'password'=>'jeanlouis12345', 'customer_id'=>2],
           
        ];
        foreach($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setName($userData['name']);
            $password = $this->hasher->hashPassword($user, $userData['password']);
            $user->setPassword($password);
            $user->setCustomer($this->getReference('customer-'.$userData['customer_id']));

            $manager->persist($user);
        }
        $manager->flush();
    }
}
