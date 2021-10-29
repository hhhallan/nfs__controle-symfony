<?php

namespace App\DataFixtures;

use App\Entity\Proprietaire;
use App\Entity\Restaurant;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        for ($i = 0; $i < 10; $i++) {

            $ville = new Ville();
            $ville->setNom($faker->city);

            for ($j = 0; $j < 5; $j++) {

                $proprio = new Proprietaire();
                $proprio->setPrenom($faker->firstName);
                $proprio->setNom($faker->lastName);
                $proprio->setDateNaissance($faker->dateTime([], 'UTC'));

                $manager->persist($proprio);

                $resto = new Restaurant();
                $resto->setNom($faker->company);
                $resto->setImage($faker->imageUrl(640, 400));
                $resto->setAdresse($faker->streetAddress);
                $resto->setVille($ville);
                $resto->setProprietaire($proprio);

                $manager->persist($resto);

            }

            $manager->persist($ville);
        }

        $manager->flush();
    }
}
