<?php

namespace Fixture;

use App\Entity\Content;
use App\Entity\Meta;
use App\Entity\Post;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BlogFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $post = new Post(
                \DateTimeImmutable::createFromMutable($faker->dateTime),
                trim($faker->sentence, '.'),
                new Content(
                    $faker->text(500),
                    $faker->paragraphs(5, true)
                ),
                new Meta(
                    trim($faker->sentence, '.'),
                    $faker->text(200)
                )
            );

            $count = random_int(0, 10);

            for ($j = 0; $j < $count; $j++) {
                $post->addComment(
                    \DateTimeImmutable::createFromMutable($faker->dateTime),
                    $faker->name,
                    $faker->text(200)
                );
            }

            $manager->persist($post);
        }
        $manager->flush();
    }
}
