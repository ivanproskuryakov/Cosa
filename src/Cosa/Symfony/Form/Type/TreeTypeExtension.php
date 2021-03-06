<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\Symfony\Form\Type;

use Symfony\Component\Form\AbstractExtension;

class TreeTypeExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return array(
            new TreeType(),
        );
    }

    protected function loadTypeGuesser()
    {
        return new TreeTypeGuesser();
    }
}

