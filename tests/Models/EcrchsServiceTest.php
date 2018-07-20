<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Models;

use AltThree\TestBench\ValidationTrait;
use CachetHQ\Cachet\Models\EcrchsService;
use CachetHQ\Tests\Cachet\AbstractTestCase;

/**
 * This is the component model test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class EcrchsServiceTest extends AbstractTestCase
{
    use ValidationTrait;

    public function testValidation()
    {
        #$this->checkRules(new EcrchsService());
    }

    public function testBasic() {
        $this->assertTrue(true);
    }
}
