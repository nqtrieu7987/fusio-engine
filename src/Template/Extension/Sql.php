<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Engine\Template\Extension;

use Fusio\Engine\Template\Parser;
use PSX\DateTime\DateTime;
use PSX\Record\RecordInterface;

/**
 * Sql
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Sql extends Text
{
    public function __construct()
    {
        parent::__construct();

        $this->setDateFormat(DateTime::SQL);
    }

    public function getFilters()
    {
        return array_merge(parent::getFilters(), [
            new \Twig_SimpleFilter('prepare', __NAMESPACE__ . '\\fusio_prepare_filter'),
        ]);
    }
}

function fusio_prepare_filter($value) {
    if ($value instanceof RecordInterface || $value instanceof \stdClass || is_array($value)) {
        $value = serialize($value);
    }

    Parser\Sql::addSqlParameter($value);

    return '?';
}
