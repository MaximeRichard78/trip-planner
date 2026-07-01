<?php

declare(strict_types=1);

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TextArrayType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function getName(): string
    {
        return '_text';
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value;
    }
}