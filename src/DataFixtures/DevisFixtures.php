<?php

namespace App\DataFixtures;

use App\Entity\Devis;
use App\Entity\Administrateur;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DevisFixtures extends Fixture
{
    public function load(ObjectManager $manager):void
    {
        $faker = Factory::create();

        // Récupérer un administrateur existant
        $admin = $manager->getRepository(Administrateur::class)->findOneBy([]);
        if (!$admin) {
            throw new \Exception('Aucun administrateur trouvé. Créez-en un d’abord.');
        }

        // Récupérer quelques clients existants
        $clients = $manager->getRepository(Client::class)->findAll();
        if (!$clients) {
            throw new \Exception('Aucun client trouvé. Créez-en quelques-uns d’abord.');
        }

        $statuses = ['nouveau', 'en_cours', 'validé', 'annulé'];

        for ($i = 0; $i < 20; $i++) {
            $devis = new Devis();
            $devis->setClient($faker->randomElement($clients));
            $devis->setAdministrateur($admin);
            $devis->setNumeroDevis('DEV-' . $faker->unique()->numerify('####'));
            $devis->setDateCreation($faker->dateTimeBetween('-2 months', 'now'));
            $devis->setStatut($faker->randomElement($statuses));
            $devis->setMontantTotal($faker->randomFloat(2, 100, 5000));
            $devis->setIsRead($faker->boolean(50)); // 50% chance que le devis soit lu ou non

            $manager->persist($devis);
        }

        $manager->flush();
    }
}
