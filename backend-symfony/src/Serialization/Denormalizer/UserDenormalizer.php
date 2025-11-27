<?php

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private UserPasswordHasherInterface $passwordHasher;
    private Security $security;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        Security $security)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [User::class => false];
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        $bool = false;
        if (!isset($context[self::ALREADY_CALLED])) {
            $bool = true;
        }

        return $bool;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;
        if (isset($data['password']) && $data['password']) {
            $user = $this->security->getUser();
            if ($user instanceof PasswordAuthenticatedUserInterface) {
                $data['password'] = $this->passwordHasher->hashPassword($user, $data['password']);
            }
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
