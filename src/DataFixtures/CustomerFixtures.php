<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class CustomerFixtures extends Fixture
{
    private $userPasswordHasher; 

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasher = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {

        $customersData=[
            ['id'=>1, 'name'=>'Sfrr', 'mail'=>'sfrr@phoneapi.com', 'password'=>'sfrr1234'],
            ['id'=>2, 'name'=>'Bouyguo', 'mail'=>'bouyguo@phoneapi.com', 'password'=>'bouyguo5678'],
            ['id'=>3, 'name'=>'fraa', 'mail'=>'fraa@phoneapi.com', 'password'=>'fraa9012'],
            ['id'=>4, 'name'=>'sitch', 'mail'=>'sitch@phoneapi.com', 'password'=>'sitch3456'],
           
        ];


        // Création des Customer
        foreach($customersData as $customerData) {
            $customer = new Customer();
            $customer->setName($customerData['name']);
            $customer->setEmail($customerData['mail']);
            $password = $this->userPasswordHasher->hashPassword($customer, $customerData['password']);
            $customer->setPassword($password);
            $this->setReference('customer-'.$customerData['id'], $customer);
    
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
