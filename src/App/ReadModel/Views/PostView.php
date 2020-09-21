<?php

declare(strict_types=1);

namespace App\ReadModel\Views;

class PostView
{
    public $id;
    public \DateTimeImmutable $date;
    public string $title;
    public string $content;
}
