<?php
class WidgetsModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */
    protected $widget;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->widget = new Core_Model_Widgets();
    }

    public function testSave()
    {
        $result = $this->widget->getWidgetsList();
        $this->assertEquals(0, count($result));

        $type = "item";
        $title = "test";
        $desc = "Some description";

        $result = $this->widget->save($title,$desc,$type);
        $this->assertEquals(1,$result);

        $result = $this->widget->getWidgetsList();
        $expectedResult = json_decode('[{"title":"test","description":"Some description","includePath":"test.phtml","numerOfRegionsArticles":0}]',true);
        unset($result[0]['id']);
        $this->assertEquals($expectedResult,$result);
    }

    public function testGetWidgetsTypes()
    {
        $expectedResult = json_decode('{"Item":{"item":"Item"},"Categories":{"category":"Category"},"Content type":{"contentType":"Content type"},"Form type":{"formType":"Form type"},"Menu":{"menu":"Menu"}}',true);
        $result = $this->widget->getWidgetsTypes();
        $this->assertEquals($expectedResult,$result);

    }
    //treba je dodatno ispitati, nisam odvaliko kako se koristi fja tj kada
    public function testGetAllArticlesForWidget()
    {
        $result = $this->widget->getWidgetsList();
        $expectedResult = json_decode('[{"title":"test","description":"Some description","includePath":"test.phtml","numerOfRegionsArticles":0}]',true);

        $id = $result[0]['id'];
        unset($result[0]['id']);
        $this->assertEquals($expectedResult,$result);

        $result = $this->widget->getAllArticlesForWidget($id);
        $this->assertEquals(0, count($result));
    }

    public function testDelete()
    {
        $result = $this->widget->getWidgetsList();
        $expectedResult = json_decode('[{"title":"test","description":"Some description","includePath":"test.phtml","numerOfRegionsArticles":0}]',true);

        $id = $result[0]['id'];
        unset($result[0]['id']);
        $this->assertEquals($expectedResult,$result);

        $result = $this->widget->delete($id);

        $result = $this->widget->getWidgetsList();
        $this->assertEquals(0, count($result));
    }
    //need to be testet when exists content
    //public function testGetOptions()
    //need to be tested when exists themes
    //public function testGetThemesList()


}
?>