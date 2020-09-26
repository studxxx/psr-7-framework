<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $this->table('posts')->truncate();

        $faker = Faker\Factory::create();
        $data = [];

        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'title' => trim($faker->sentence, '.'),
                'date' => $faker->date('Y-m-d H:i:s'),
                'content' => $faker->text(500),
            ];
        }

        $this->insert('posts', $data);
    }
}
