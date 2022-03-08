<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Car;
use App\Entity\Brand;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { 
        $brand = new Brand;
        $brand->setName('Toyato');
        $manager->persist( $brand);
        $brand = new Brand;
        $brand->setName('Bentlay');
        $manager->persist( $brand);
        $brand = new Brand;
        $brand->setName('Renault');
        $manager->persist( $brand);
        $brand = new Brand;
        $brand->setName('Peugeot');
        $manager->persist( $brand);
        $brand = new Brand;
        $brand->setName('Citroën');
        $manager->persist( $brand);

        











        $user = new User();
        $user->setEmail('admin@admin.com');
        // password is admin
        $user->setPassword('$2y$13$DN.gtv4z9bc0IOcpqkkDNuVdBg/nOQq2/yIDaf9E/TWf9o3Os8vyu');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('manager@manager.com');
        // password is manager
        $user->setPassword('$2y$13$DN.gtv4z9bc0IOcpqkkDNuVdBg/nOQq2/yIDaf9E/TWf9o3Os8vyu');
        $user->setRoles(['ROLE_MANAGER']);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user@user.com');
        // password is user
        $user->setPassword('$2y$13$DN.gtv4z9bc0IOcpqkkDNuVdBg/nOQq2/yIDaf9E/TWf9o3Os8vyu');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        

        $manager->flush();
    }
}
