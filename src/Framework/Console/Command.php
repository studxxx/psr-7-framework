<?php

declare(strict_types=1);

namespace Framework\Console;

abstract class Command
{
    private string $name;
    private string $description;

    public function __construct(string $name = null)
    {
        if ($name === null) {
            $this->setName(static::class);
        } else {
            $this->setName($name);
        }

        $this->configure();
    }

    protected function configure(): void
    {
    }

    abstract public function execute(Input $input, Output $output): void;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
