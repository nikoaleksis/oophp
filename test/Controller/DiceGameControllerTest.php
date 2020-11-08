<?php

namespace Niko\Controller;

use Anax\DI\DIMagic;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Dicegamecontroller.
 */
class DiceGameControllerTest extends TestCase
{
    private $controller;

    /**
     * Sets up the objects needed for testing.
     * @return void
     */
    protected function setUp()
    {
        //init the service container
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $di->set("app", $app);

        $this->controller = new DiceGameController();
        $this->controller->setApp($app);
    }

    /**
     * Tests the initGameAction method and that it returns a ResponseUtility object.
     * @return void
     */
    public function testInitActionGet()
    {
        $res = $this->controller->initActionGet();

        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
