<?php
class SiteTest extends WebTestCase
{
    public $fixtures=array(
        'agents'=>'Agent',
        'realties'=>'Realty',
        'filters'=>':funda_filter',
        'pages'=>':funda_page',
    );

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl('http://localhost:8888');
    }

    public function testIndex()
    {
        $this->open('');

        $this->assertTextPresent('Insided Funda.nl Assignment');

        $this->assertTextPresent('Top agents - houses, Amsterdam, all');
        $this->assertEquals(10, $this->getXpathCount('//table[1]//tbody/tr'));

        $this->assertTextPresent('Top agents - houses, Amsterdam, with garden');
        $this->assertEquals(10, $this->getXpathCount('//table[2]//tbody/tr'));
    }
}
