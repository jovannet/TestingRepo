<?php
class ErrorModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */
  
    protected $error;
    
    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {

        parent::setUp();
        $this->error = new Cms_Model_Error();

    }
    
    public function testGetErrors()
    {
        $expectedResult = json_decode('[{"id":1,"type":"404","text":"Error 404 ocurred.","debugInfo":1},{"id":2,"type":"500","text":"Internal server error 500 ocurred.","debugInfo":1}]',true);
        $result =  $this->error->getErrors();
        
        $this->assertEquals($result,$expectedResult);
    }
    
    public function testGetBugable()
    {
        $this->assertTrue(!$this->error->getBugable(0));
    }

    public function testSetErrors()
    {
        //update
        $values = json_decode('{"controller":"error","action":"errorcontroll","module":"cms","errorText404":"Error 404 ocurred.TEST","id404":"1","errorText500":"Internal server error 500 ocurred.TEST","id500":"2"}',true);
        $this->assertTrue($this->error->setErrors($values));
        //check update changes
        $result = $this->error->getErrors();
        $expectedResult = json_decode('[{"id":1,"type":"404","text":"Error 404 ocurred.TEST","debugInfo":0},{"id":2,"type":"500","text":"Internal server error 500 ocurred.TEST","debugInfo":0}]',true);
        $this->assertEquals($result,$expectedResult);
        //update to old values 
        $values = json_decode('{"controller":"error","action":"errorcontroll","module":"cms","errorText404":"Error 404 ocurred.","id404":"1","checkbox404":"","errorText500":"Internal server error 500 ocurred.","id500":"2","checkbox500":""}',true);
        $this->assertTrue($this->error->setErrors($values));
        //check for old values
        $expectedResult = json_decode('[{"id":1,"type":"404","text":"Error 404 ocurred.","debugInfo":1},{"id":2,"type":"500","text":"Internal server error 500 ocurred.","debugInfo":1}]',true);
        $result =  $this->error->getErrors();        
        $this->assertEquals($result,$expectedResult);
    }
    
    public function testGetErrorText()
    {
        $this->assertEquals("Error 404 ocurred.",$this->error->getErrorText(404));
    }
}
?>