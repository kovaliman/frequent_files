<?php

namespace KovaLiman\FrequentlyFiles\DTO;

class DTOPropertyParser
{
    public static function parseProperties($properties): string
    {
        $total = count($properties);
        if (!$total) {
            return "//todo";
        }
        $data = '';
        for ($i = 0; $i <= $total - 1; $i++) {
            $t = self::mapType($properties[$i]->type);
            $data .= "public ?" . $t . " $" . $properties[$i]->name . " = null;";
            if ($i < $total - 1) {
                $data .= "\n\t";
            }
        }
        return $data;
    }

    private static function mapType(string $type): string
    {
        if ($type === 'boolean') {
            return 'bool';
        }
        if ($type === 'integer') {
            return 'int';
        }

        return 'string';
    }
}