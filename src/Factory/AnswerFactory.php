<?php

namespace App\Factory;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use http\QueryString;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service_locator;

/**
 * @extends ModelFactory<Answer>
 *
 * @method static Answer|Proxy createOne(array $attributes = [])
 * @method static Answer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Answer|Proxy find(object|array|mixed $criteria)
 * @method static Answer|Proxy findOrCreate(array $attributes)
 * @method static Answer|Proxy first(string $sortedField = 'id')
 * @method static Answer|Proxy last(string $sortedField = 'id')
 * @method static Answer|Proxy random(array $attributes = [])
 * @method static Answer|Proxy randomOrCreate(array $attributes = [])
 * @method static Answer[]|Proxy[] all()
 * @method static Answer[]|Proxy[] findBy(array $attributes)
 * @method static Answer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Answer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnswerRepository|RepositoryProxy repository()
 * @method Answer|Proxy create(array|callable $attributes = [])
 */
final class AnswerFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'content' => self::faker()->text(),
            'username' => self::faker()->userName(),
            'votes' => self::faker()->numberBetween(-20,50),
            'createdAt' => self::faker()->dateTimeThisYear(),
            'question' => QuestionFactory::random(),
            'status' => self::faker()->randomElement(Answer::QUESTION_STATUS)
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Answer $answer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Answer::class;
    }
}
