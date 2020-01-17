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
 * in the hope  that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (is_array($env = @include dirname(__DIR__).'/.env.local.php') &&
    ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? $env['APP_ENV'] ?? null) === ($env['APP_ENV'] ?? null)
) {
    foreach ($env as $k => $v) {
        $_ENV[$k] = $_ENV[$k] ?? (isset($_SERVER[$k]) && 0 !== strpos($k, 'HTTP_') ? $_SERVER[$k] : $v);
    }
} elseif (!class_exists(Dotenv::class)) {
    $message = 'Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.';

    throw new RuntimeException($message);
} else {
    (new Dotenv(false))->loadEnv(dirname(__DIR__).'/.env');
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] =
    (int) $_SERVER['APP_DEBUG'] ||
    filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
