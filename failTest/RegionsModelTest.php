<?php
class RegionsModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */
    protected $region;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->region = new Core_Model_Regions();
    }
    /* getAllRegions() metoda je testirana kroz ove dve metode, mislim da ne treba da se naknadno testira bilo bi samo ponavljanje koda, ako bude trebalo dodacemo je.*/
    //tested  methods getAllRegions(),save($title)
    public function testAddReginos()
    {
        //test if returns empty regions
        $regions = $this->region->getAllRegions();
        $this->assertEquals(0,count($regions));

        //titile for testing region
        $title = "new region";

        //test if success save
        $this->assertTrue($this->region->save($title));

        $expectedArray =array(array("name"=> "new region","numArticles"=> 0));
        //test for new region in database
        $regions = $this->region->getAllRegions();
        unset($regions[0]['id']); //id is a variable (it's never same) in array
        $this->assertEquals($regions,$expectedArray);
    }

    //tested methods getAllRegions(),delete($regionId)
    public function testDeleteRegions()
    {
        //test if returns one region
        $regions = $this->region->getAllRegions();
        $this->assertEquals(1,count($regions));

        //delete region
        $result = $this->region->delete($regions[0]["id"]);
        $this->assertTrue($result);

        $regions = $this->region->getAllRegions();
        //test for no regions in database
        $this->assertEquals(0,count($regions));
    }
}
?>