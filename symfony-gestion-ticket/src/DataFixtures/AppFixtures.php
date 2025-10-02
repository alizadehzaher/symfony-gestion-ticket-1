<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Administrateur');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        $staff1 = new User();
        $staff1->setEmail('staff@example.com');
        $staff1->setName('Employé 1');
        $staff1->setRoles(['ROLE_STAFF']);
        $staff1->setPassword($this->passwordHasher->hashPassword($staff1, 'password'));
        $manager->persist($staff1);

        $staff2 = new User();
        $staff2->setEmail('staff2@example.com');
        $staff2->setName('Employé 2');
        $staff2->setRoles(['ROLE_STAFF']);
        $staff2->setPassword($this->passwordHasher->hashPassword($staff2, 'password'));
        $manager->persist($staff2);

        // Création de tickets de test
        $categories = ['Incident', 'Panne', 'Évolution', 'Anomalie', 'Information'];
        $statuses = ['Nouveau', 'Ouvert', 'Résolu', 'Fermé'];
        $emails = ['client1@example.com', 'client2@example.com', 'client3@example.com'];

        for ($i = 0; $i < 10; $i++) {
            $ticket = new Ticket();
            $ticket->setAuthor($emails[array_rand($emails)]);
            $ticket->setDescription('Description du ticket #' . ($i + 1) . '. Ceci est une description détaillée du problème rencontré par le client.');
            $ticket->setCategory($categories[array_rand($categories)]);
            $ticket->setStatus($statuses[array_rand($statuses)]);
            
            if ($ticket->getStatus() === 'Fermé') {
                $ticket->setCloseDate(new \DateTime('-' . rand(1, 30) . ' days'));
            }
            
            if (rand(0, 1)) {
                $ticket->setResponsible([$admin, $staff1, $staff2][array_rand([$admin, $staff1, $staff2])]);
            }

            $manager->persist($ticket);
        }

        $manager->flush();
    }
}