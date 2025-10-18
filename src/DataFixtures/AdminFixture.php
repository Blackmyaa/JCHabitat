<?php

namespace App\DataFixtures;
use App\Entity\Administrateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdminFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crée un administrateur
        $admin = new Administrateur();
        $admin->setNom('Admin');
        $admin->setPrenom('Principal');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ADMIN']);
        $admin->setPassword(password_hash('Basilou9', PASSWORD_BCRYPT)); // si tu gères un mot de passe

        $manager->persist($admin);

        // Valide et écrit en base
        $manager->flush();
    }
}
