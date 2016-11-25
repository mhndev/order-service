<?php
namespace Tests\Functional;

/**
 * Class OrderControllerTest
 * @package Tests\Functional
 */
class OrderControllerTest extends BaseTestCase
{

    public function testGetAnOrder()
    {
        $response = $this->runApp('GET', '/582f1e975bc8e1067767f6d4');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('582f1e975bc8e1067767f6d4',
            json_decode($response->getBody()->__toString(), true)['identifier'] );
    }

}
