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

namespace Fusio\Engine\Schema;

use PSX\Schema\Parser\JsonSchema;
use PSX\Schema\Parser\JsonSchema\RefResolver;
use PSX\Schema\Property\ComplexType;
use PSX\Validate;
use RuntimeException;

/**
 * Parser
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Parser implements ParserInterface
{
    /**
     * Parses and resolves the json schema source and returns the object
     * presentation of the schema
     *
     * @param string $source
     * @return string
     */
    public function parse($source)
    {
        $resolver = new RefResolver();
        $parser   = new JsonSchema(null, $resolver);
        $schema   = $parser->parse($source);

        if (!$schema->getDefinition() instanceof ComplexType) {
            throw new RuntimeException('Schema must be an object');
        }

        return serialize($schema);
    }
}
