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

namespace Fusio\Engine\Parser;

use FilesystemIterator;
use Fusio\Engine\Factory\FactoryInterface;
use Fusio\Engine\Form;

/**
 * Directory
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Directory extends ParserAbstract
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $instanceOf;

    /**
     * @param \Fusio\Engine\Factory\FactoryInterface $factory
     * @param \Fusio\Engine\Form\ElementFactoryInterface $elementFactory
     * @param string $directory
     * @param string $namespace
     * @param string $instanceOf
     */
    public function __construct(FactoryInterface $factory, Form\ElementFactoryInterface $elementFactory, $directory, $namespace, $instanceOf)
    {
        parent::__construct($factory, $elementFactory);

        $this->directory  = $directory;
        $this->namespace  = $namespace;
        $this->instanceOf = $instanceOf;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        $result = array();

        if (is_dir($this->directory)) {
            $iterator = new FilesystemIterator($this->directory);
            foreach ($iterator as $file) {
                if ($file->getExtension() == 'php') {
                    $class      = $this->namespace . '\\' . $file->getBasename('.php');
                    $object     = $this->getObject($class);
                    $instanceOf = $this->instanceOf;

                    if ($object instanceof $instanceOf) {
                        $result[] = array(
                            'name'  => $object->getName(),
                            'class' => $class,
                        );
                    }
                }
            }
        }

        return $result;
    }
}
