<?php

namespace App\Factory;

use App\Entity\Question;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Question>
 */
final class QuestionFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Question::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $mediaTypeList = ['image', 'video', 'audio', 'text'];
        $mediaType = self::faker()->randomElement($mediaTypeList);
        if ('text' === $mediaType) {
            $mediaUrl = null;
        } else {
            $mediaUrl = self::faker()->url();
        }

        return [
            'mediaType' => $mediaType,
            'mediaUrl' => $mediaUrl,
            'questionText' => rtrim(self::faker()->sentence(), '.').' ?',
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Question $question): void {})
        ;
    }
}
