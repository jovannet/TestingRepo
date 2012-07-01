<?php
class ManageContentModelTest extends ControllerTestCase
{
    /**
     * Formtypes model variable
     *
     * @var CMS_Model_Formtypes
     */
    protected $content;
    protected $contentType;
    protected $dbAdapter;
    protected $lang;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->content     = new Core_Model_ManageContent();
        $this->contentType = new Core_Model_Contenttypes();
        $this->lang        = new Core_Model_Language();
        $this->dbAdapter  = Zend_Registry::getInstance()->dbAdapter;
    }


    /**
    * Testing inserting form type and fetching form types list.
    *
    */
    public function testCreateTestContentType()
    {
        //test if returns empty contentType
        $contentTypes = $this->contentType->getContentTypesList();
        $this->assertEquals(0,count($contentTypes));
        $values = json_decode('{"controller":"contenttypes","action":"save","module":"cms","newContentType":"new contenttype","fieldTttle_2":"filedCT","fieldType_2":"text","fieldDefaultValues_2":"default","fieldShowInList_2":"1","fieldSearchable_2":"1","fieldTitleField_123":"filedCT","errorRegexpFail":"You can use only letters, numbers, space and question mark in length of 64 characters!","errorTitleFieldMsg":"You need to select one Title field!","errorEmptyContentTypeTitle":"Please enter Content Type title!","emptyFieldTitleMsg":"You need to enter title of field!","errorTitleAllreadyExist":"Title exists allready. Use a different one."}',true);
        $this->assertTrue($this->contentType->createNewContentType($values));

        //test if returns one contentType
        $expectedArray = array(array("title" => "new contenttype","tableName" => "_new_contenttype","regions"=>1));
        $contentTypes = $this->contentType->getContentTypesList();
        unset($contentTypes[0]["id"]);
        $this->assertEquals($contentTypes,$expectedArray);

    }



    public function testsaveNewArticle()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $fcid = $this->dbAdapter->fetchOne("select id from contentTypeFileds");

        $values = json_decode('{"controller":"manage","action":"save","contentType":"'.$cid.'","module":"cms","filedct_en":"test","filedct_sr":"","menu":"0"}',true);
        $type = json_decode('{"id":'.$cid.',"title":"new contenttype","tableName":"_new_contenttype","regions":1,"fields":[{"id":'.$fcid.',"contentTypeId":'.$cid.',"title":"filedCT","fieldType":"text","defaultValue":"","showInList":1,"titleField":1,"searchable":1}],"listFields":[{"title":"filedCT"}]}',true);
        $languages = $this->lang->getActiveLanguages();

        $result = $this->content->saveNewArticle($values,$type,$languages);
        $this->assertTrue($result);
    }

    public function testGetContentType()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $result = $this->content->getContentType($cid);
        $fcid = $this->dbAdapter->fetchOne("select id from contentTypeFileds");

        $expectedArray = json_decode('{"id":'.$cid.',"title":"new contenttype","tableName":"_new_contenttype","regions":1,"fields":[{"id":'.$fcid.',"contentTypeId":'.$fcid.',"title":"filedCT","fieldType":"text","defaultValue":"default","showInList":1,"titleField":1,"searchable":1}],"listFields":[{"title":"filedCT"}]}',true);
        $this->assertEquals($expectedArray,$result);
    }

    public function testGetContentTypeTableName()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $result = $this->content->getContentTypeTableName($cid);
        $this->assertEquals("_new_contenttype",$result);
    }

    public function testGetArticleList()
    {
        $table = "_new_contenttype";
        $fields = "`_new_contenttype`.filedct,`_new_contenttype`.`id`";
        $contentTypes = $this->contentType->getContentTypesList();
        $category = "";
        $cid = $contentTypes[0]["id"];

        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");
        $expectedArray = json_decode('[{"filedct":"test","id":'.$ids["id"].',"resourceId":'.$ids["resourceId"].',"category":"default"}]',true);
        $result = $this->content->getArticleList($table,$fields,1,$category,$cid);
        $this->assertEquals($result,$expectedArray);
    }

    public function testGetArticlesListSearch()
    {
        $table = "_new_contenttype";
        $fields = "`_new_contenttype`.filedct,`_new_contenttype`.`id`";
        $searchString ="te";
        $contentTypes = $this->contentType->getContentTypesList();
        $category = "";
        $cid = $contentTypes[0]["id"];
        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");
        $titleField = "filedct";

        $expectedArray = json_decode('[{"filedct":"test","id":'.$ids["id"].',"resourceId":'.$ids["resourceId"].',"category":"default"}]',true);
        $result = $this->content->getArticlesListSearch($table,$fields,$searchString,$category,$cid,$titleField);
        $this->assertEquals($expectedArray,$result);

        $result = $this->content->getArticlesListSearch($table,$fields,"Q",$category,$cid,$titleField);
        $this->assertEquals(array(),$result);
    }

    public function testGetContentList()
    {

        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");
        $expectedArray = json_decode('{"_new_contenttype:::filedCT":[{"id":1,"resourceId":'.$ids["resourceId"].',"lang":"en","url":"\/content\/article\/page\/test","filedCT":"test","contentTypeId":'.$cid.'},{"id":2,"resourceId":'.$ids["resourceId"].',"lang":"sr","url":null,"filedCT":"0","contentTypeId":'.$cid.'}]}',true);

        $result = $this->content->getContentList();

        $this->assertEquals($result,$expectedArray);
    }

    public function testCopyArticle()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");
        $fcid = $this->dbAdapter->fetchOne("select id from contentTypeFileds");

        $values = json_decode('{"controller":"manage","action":"copy","contentType":"'.$cid.'","id":"1","page":"1","module":"cms"}',true);
        $type = json_decode('{"id":'.$cid.',"title":"new contenttype","tableName":"_new_contenttype","regions":1,"fields":[{"id":'.$fcid.',"contentTypeId":'.$cid.',"title":"filedCT","fieldType":"text","defaultValue":"","showInList":1,"titleField":1,"searchable":1}],"listFields":[{"title":"filedCT"}]}',true);

        $result = $this->content->copyArticle($values,$type);
        $this->assertTrue($result);

    }

    public function testDeleteArticle()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];

        $this->assertTrue($this->content->deleteArticle($cid,3));
        $result = $this->content->getContentList();
        $this->assertEquals(count($result["_new_contenttype:::filedCT"]),2);
    }

    public function testFullEdit()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");

        $expectedArray = json_decode('{"table":"_new_contenttype","content":{"en":{"id":1,"resourceId":'.$ids["resourceId"].',"itemOrder":1,"lang":"en","filedct":"test"},"sr":{"id":2,"resourceId":'.$ids["resourceId"].',"itemOrder":1,"lang":"sr","filedct":"0"}},"cid":316,"categories":[{"id":316,"title":"default","contentTypeId":'.$cid.',"gallery":0,"files":0,"systemCategory":1,"selected":true}],"defaultValues":[{"title":"filedCT","defaultValue":"default"}],"resourceId":'.$ids["resourceId"].',"aid":1,"articleTitle":"test","regionArticles":""}',true);
        $result = $this->content->fulledit($cid,1);

        unset($result['categories'][0]['id']);
        unset($expectedArray['categories'][0]['id']);
        unset($expectedArray['cid']);
        unset($result['cid']);

        $this->assertEquals($expectedArray,$result);
    }

    public function testGetArticleTitle()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $id = 1;

        $result = $this->content->getArticleTitle($cid,$id);
        $this->assertEquals($result,"test");
    }

    public function testEditTitleFiled()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $id = 1;
        $expectedResult = json_decode('{"titleField":"filedCT","values":[],"col":"filedct"}',true);
        $result = $this->content->editTitleFiled($cid,$id);
        $this->assertEquals($result,$expectedResult);
    }

    public function testGetTitleFiledColumn()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];

        $result = $this->content->getTitleFiledColumn($cid);
        $this->assertEquals("filedct",$result);
    }

    public function testFullUpdate()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $ids = $this->dbAdapter->fetchRow("select * from _new_contenttype");
        $values = json_decode('{"page":"1","filedct_en":"test1","filedct_sr":"0","applayHidden":"0"}',true);
        $langs = json_decode('[{"id":1,"code":"en","title":"English","isDefault":1,"default_locale":"en_US"},{"id":51,"code":"sr","title":"Serbian","isDefault":0,"default_locale":"sr_RS"}]',true);
        $categories = array("345");

        $this->content->fullUpdate($values,$cid,$langs,$categories,$ids["resourceId"]);

        $result = $this->content->getArticleTitle($cid,1);

        $this->assertEquals($result,"test1");
    }

    public function testGetCategoryIdFromTitle()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $categoryTitle = "default";

        $expectedResult = $this->dbAdapter->fetchOne("select id from categories");

        $result = $this->content->getCategoryIdFromTitle($categoryTitle, $cid);
        $this->assertEquals($result,$expectedResult);

    }

    public function testOrderContentItemsInsideCategory()
    {
        $contentTypes = $this->contentType->getContentTypesList();
        $cid = $contentTypes[0]["id"];
        $fcid = $this->dbAdapter->fetchOne("select id from contentTypeFileds");

        $values = json_decode('{"controller":"manage","action":"save","contentType":"'.$cid.'","module":"cms","filedct_en":"test","filedct_sr":"","menu":"0"}',true);
        $type = json_decode('{"id":'.$cid.',"title":"new contenttype","tableName":"_new_contenttype","regions":1,"fields":[{"id":'.$fcid.',"contentTypeId":'.$cid.',"title":"filedCT","fieldType":"text","defaultValue":"","showInList":1,"titleField":1,"searchable":1}],"listFields":[{"title":"filedCT"}]}',true);
        $languages = $this->lang->getActiveLanguages();

        $result = $this->content->saveNewArticle($values,$type,$languages);
        $this->assertTrue($result);

        $items = array(1,4);
        $categoryId = 374;
        $tableName = "_new_contenttype";
        $result = $this->content->orderContentItemsInsideCategory($items, $categoryId, $tableName);
        $expectedArray = array("status"=>true,"code"=>"Orderd sucessfuly!");
        $this->assertEquals($result, $expectedArray);
    }

    public function testOrderContentItemsInsideContentType()
    {
        $tableName = "_new_contenttype";
        $items = array(1,4);

        $result = $this->content->orderContentItemsInsideContentType($items, $tableName);
        $this->assertEquals($result, array("status"=>true,"code"=> "Orderd sucessfuly!"));
    }

    public function testsGetArticleId()
    {
        $tableName = "_new_contenttype";
        $fcid = $this->dbAdapter->fetchOne("select id from contentTypeFileds");
        $lang = "en";

        $result = $this->content->getArticleId($fcid["resourceId"],$tableName,$lang);
    }
    //mora da se testira u odvojenom testu unakrsnom koji treba napistati
    public function testRemoveWidget()
    {

    }

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