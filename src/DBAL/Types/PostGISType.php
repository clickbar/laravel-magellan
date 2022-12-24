<?php

/**
 * This file is part of the Doctrine PostGIS project.
 * https://github.com/jsor/doctrine-postgis
 *
 * Licensed under the MIT license:
 *
 *   Copyright (c) 2014-2022 Jan Sorgalla
 *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy
 *   of this software and associated documentation files (the "Software"), to deal
 *   in the Software without restriction, including without limitation the rights
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *   copies of the Software, and to permit persons to whom the Software is furnished
 *   to do so, subject to the following conditions:
 *
 *   The above copyright notice and this permission notice shall be included in all
 *   copies or substantial portions of the Software.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *   THE SOFTWARE.
 *
 * https://github.com/jsor/doctrine-postgis/blob/be6d80de02bcf0ec59c1bb98b5e2953f7d3b980b/src/Types/PostGISType.php
 */

declare(strict_types=1);

namespace Clickbar\Magellan\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class PostGISType extends Type
{
    public const GEOMETRY = 'geometry';

    public const GEOGRAPHY = 'geography';

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        // @phpstan-ignore-next-line
        return [$this->getName()];
    }

    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return sprintf('ST_AsEWKT(%s)', $sqlExpr);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return sprintf('ST_GeomFromEWKT(%s)', $sqlExpr);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var array{geometry_type?: string|null, srid?: int|string|null} $column */
        $options = $this->getNormalizedPostGISColumnOptions($column);

        return sprintf(
            '%s(%s, %d)',
            // @phpstan-ignore-next-line
            $this->getName(),
            $options['geometry_type'],
            $options['srid']
        );
    }

    /**
     * @param  array{geometry_type?: string|null, srid?: int|string|null}  $options
     * @return array{geometry_type: string, srid: int}
     */
    abstract public function getNormalizedPostGISColumnOptions(array $options = []): array;
}
