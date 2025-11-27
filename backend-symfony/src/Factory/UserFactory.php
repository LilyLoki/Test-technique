<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $firstname = UserFactory::normalizeName(self::faker()->firstName());
        $lastname = UserFactory::normalizeName(self::faker()->lastName());

        return [
            'email' => $firstname.'.'
                .$lastname.'@'.self::faker()->domainName(),
            'password' => 'test',
            'roles' => [],
            'username' => self::faker()->unique()->numerify("{$firstname}###"),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
             ->afterInstantiate(function (User $user): void {
                 $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
             });
    }

    protected function normalizeName(string $chaine): string
    {
        return mb_strtolower(
            preg_replace('/[^a-zA-Z0-9]/', '-',
                transliterator_transliterate('Any-Lower; Latin-ASCII', $chaine)));
    }
}
