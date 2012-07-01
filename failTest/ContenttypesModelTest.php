<?php
class ContenttypesModelTest extends ControllerTestCase
{

    /**
     * Region model variable
     *
     * @var CMS_Model_Contenttypes
     */
    protected $contentType;
    protected $dbAdapter;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->contentType = new Core_Model_Contenttypes();
        $this->dbAdapter = Zend_Registry::getInstance()->dbAdapter;
    }

    /**
     * tested getContentTypesList(), createNewContentType($values),
     *
     */
    public function  testAddContentType()
    {
        //test if returns empty contentType
        $contentTypes = $this->contentType->getContentTypesList();
        $this->assertEquals(0,count($contentTypes));
        $values = json_decode('{"controller":"contenttypes","action":"save","module":"cms","newContentType":"new contenttype","fieldTttle_2":"filedCT","fieldType_2":"wysiwyg","fieldDefaultValues_2":"default","fieldShowInList_2":"1","fieldSearchable_2":"1","fieldTitleField_123":"filedCT","errorRegexpFail":"You can use only letters, numbers, space and question mark in length of 64 characters!","errorTitleFieldMsg":"You need to select one Title field!","errorEmptyContentTypeTitle":"Please enter Content Type title!","emptyFieldTitleMsg":"You need to enter title of field!","errorTitleAllreadyExist":"Title exists allready. Use a different one."}',true);
        $this->assertTrue($this->contentType->createNewContentType($values));

        //test if returns one contentType
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        $contentTypes = $this->contentType->getContentTypesList();
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);
    }

    public function testgetContentType(){

        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);

        //test if geting contenttype well
        $exArray = array("title" => "new contenttype","tableName" => "_new_contenttype", "regions" => 1, "fields" => array(array("title" => "filedCT", "fieldType" => "wysiwyg", "defaultValue" => "default", "showInList" => 1, "titleField" => 1, "searchable" => 1)),"listFields" => array(array("title"=>"filedCT")));
        $article = $this->contentType->getContentType($cid);
        unset($article["id"]);
        unset($article["fields"][0]["id"]);
        unset($article["fields"][0]["contentTypeId"]);
        $this->assertEquals($article, $exArray);

    }

    public function testGetFieldType()
    {
         //test if returns one contentType
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);

        $fieldType = $this->contentType->getFieldType($cid, "filedCT");
        $this->assertEquals($fieldType, 'wysiwyg');

    }

    public function testDeleteColumn()
    {
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);
        //geting fid
        $article = $this->contentType->getContentType($cid);
        $fid = $article["fields"][0]["id"];
        unset($article);
        //test method
        $this->assertEquals(array("status" => true, "code" => "deletecolumnOk"),$this->contentType->deleteColumn($cid,$fid));
    }

    /*
    //Problem je sto moram da prosledim ID field ali tacan moram da vadim to upitom iz baze
    public function  testUpdateContentType()
    {
        //existing values
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        //test if returns empty contentType
        $contentTypes = $this->contentType->getContentTypesList();
        $id = $contentTypes[0]["id"];
        $idField = $this->dbAdapter->fetchOne("select id from contentTypeFileds where contentTypeId = ?",$id);
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);
        //update
        $values = json_decode('{"controller":"contenttypes","action":"update","module":"cms","contentTypeId":"'.$id.'","title":"new contenttype1","title_'.$idField.'":"filedct1","contentType_'.$idField.'":"wysiwyg","defaultValues_'.$idField.'":"default","showInList_'.$idField.'":"1","searchable_'.$idField.'":"1","titleField":"822","errorTitleFieldMsg":"You need to select one Title field!","errorEmptyContentTypeTitle":"Please enter Content Type title!","emptyFieldTitleMsg":"You need to enter title of field!","errorTitleAllreadyExist":"Title exists allready. Use a different one."}',true);
        $this->assertTrue($this->contentType->update($values));

        $expectedArray = array(array("title" => "new contenttype1","tableName" => "_new_contenttype1","regions"=>1));

        //test if returns one contentType
        $contentTypes = $this->contentType->getContentTypesList();
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);
    }
 */
    /**
     * tested getContentTypesList(), deleted($id)
     *
     */

    public function testDeleteContentType()
    {
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));

        //test if returns one contentType
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);

        $this->assertTrue($this->contentType->delete($cid));

        //test if returns empty contentType
        $contentTypes = $this->contentType->getContentTypesList();
        $this->assertEquals(0,count($contentTypes));
    }

}
?>