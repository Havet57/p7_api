<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Customer;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customersData=[
            ['id'=>1, 'name'=>'Sfrr'],
            ['id'=>2, 'name'=>'Bouyguo'],
            ['id'=>3, 'name'=>'fraa'],
            ['id'=>4, 'name'=>'sitch'],
           
        ];
        foreach($customersData as $customerData) {
            $customer = new Customer();
            $customer->setName($customerData['name']);
            $this->setReference('customer-'.$customerData['id'], $customer);

            $manager->persist($customer);
        }
        $manager->flush();

    }
}
