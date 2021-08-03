<?php

declare(strict_types=1);

/*
 * This file is part of «Birchbark Literacy from Medieval Rus» database.
 *
 * Copyright (c) Department of Linguistic and Literary Studies of the University of Padova
 *
 * «Birchbark Literacy from Medieval Rus» database is free software:
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation, version 3.
 *
 * «Birchbark Literacy from Medieval Rus» database is distributed
 * in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

namespace App\Helper;

abstract class StringHelper
{
    public static function removeFromStart(string $source, string $search): string
    {
        if (self::startsWith($source, $search)) {
            return substr($source, \strlen($search));
        }

        return $source;
    }

    public static function removeFromEnd(string $source, string $search): string
    {
        if (self::endsWith($source, $search)) {
            return substr($source, 0, \strlen($source) - \strlen($search));
        }

        return $source;
    }

    public static function replaceStart(string $source, string $search, string $replace): string
    {
        if (self::startsWith($source, $search)) {
            return substr_replace($source, $replace, 0, \strlen($search));
        }

        return $source;
    }

    public static function replaceEnd(string $source, string $search, string $replace): string
    {
        if (self::endsWith($source, $search)) {
            return substr_replace($source, $replace, -\strlen($search));
        }

        return $source;
    }

    public static function startsWith(string $source, string $search): bool
    {
        return 0 === mb_strpos($source, $search);
    }

    public static function endsWith(string $source, string $search): bool
    {
        return strpos($source, $search) === \strlen($source) - \strlen($search);
    }

    public static function isLowercased(string $source): bool
    {
        return mb_strtolower($source) === $source;
    }
}
