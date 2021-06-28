<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin
            ->setLastname('nsingi')
            ->setFirstname('benjamin')
            ->setEmail('b.nsingi@contact.com')
            ->setPassword(
                $this->passwordHasher->hashPassword($admin, 'admin123')
            )
            ->setRoles(User::ROLE_ADMIN)
        ;

        $manager->persist($admin);

        $user = new User();

        $user
            ->setLastname('melanie')
            ->setFirstname('dupond')
            ->setEmail('melanie.dupond@contact.com')
            ->setPassword(
                $this->passwordHasher->hashPassword($user, 'user123')
            )
            ->setRoles(User::ROLE_USER)
        ;

        $manager->persist($user);

        $manager->flush();
    }
}