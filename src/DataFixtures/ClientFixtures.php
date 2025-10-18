<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Utilisation du format français

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setNom($faker->lastName)
                   ->setPrenom($faker->firstName)
                   ->setAdresse($faker->streetAddress)
                   ->setCodePostal($faker->postcode)
                   ->setVille($faker->city)
                   ->setTelephone($faker->phoneNumber)
                   ->setEmail($faker->unique()->email);

            $manager->persist($client);
        }

        $manager->flush();
    }
}

