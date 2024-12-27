<?php


namespace KovaLiman\FrequentlyFiles\DTO;

class PropertyDTO
{
    public $name;
    public $type;
    public $required;

    public function __construct(string $name, string $type, bool $required)
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
    }
}