<?php

declare(strict_types=1);

namespace App\ReadModel\Views;

class PostView
{
    public $id;
    public \DateTimeImmutable $date;
    public string $title;
    public string $content;

    public function __construct($id, \DateTimeImmutable $date, string $title, string $content)
    {
        $this->id = $id;
        $this->date = $date;
        $this->title = $title;
        $this->content = $content;
    }
}
