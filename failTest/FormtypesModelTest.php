<?php
class FormtypesModelTest extends ControllerTestCase
{
    /**
     * Formtypes model variable
     *
     * @var CMS_Model_Formtypes
     */
    protected $formType;
    protected $dbAdapter;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->formType = new Cms_Model_Formtypes();       
        $this->dbAdapter = Zend_Registry::getInstance()->dbAdapter;
    }
    
    /**
    * Testing inserting form type and fetching form types list.
    *
    */
    public function testGetFormTypesListAndCreateNewFromType() 
    {
        //Testing return empty list
        $formTypes = $this->formType->getFormTypesList();
        $this->assertEquals(0,count($formTypes));

        $values = json_decode('{"controller":"formtypes","action":"save","module":"cms","newFormType":"Test form type","sendToEmail":"1","newFormEmail":"test@test.com","redirectUrl":"","fieldTttle_2":"test","fieldType_2":"text","fieldDefaultValues_2":"","fieldShowInList_2":"0","fieldRequired_2":"0","fieldTitleField_123":"test","errorTitleFieldMsg":"You need to select one Title field!","errorEmptyFormTypeTitle":"Please enter Form Type title!","emptyFieldTitleMsg":"You need to enter title of field!","errorRegexpFail":"You can use only letters, numbers, space and question mark in length of 64 characters!","errorTitleAllreadyExist":"Title exists allready. Use a different one."}',true);

        $this->assertTrue($this->formType->createNewFormType($values));

        //test if returns one formType 
        $expectedArray = array(0 => array("title" => "Test form type", "tableName" => "_form_test_form_type", "sendToEmail" => 1, "emailToSend" => "test@test.com", "redirectArticleId" => 0, "redirectContentTypeId" => 0 ));  

        $formTypes = $this->formType->getFormTypesList();
        unset($formTypes[0]["id"]);
        $this->assertEquals($formTypes,$expectedArray);
    }
    
    /**
    * Testing fetch of single form type for edit form.
    *
    */
    public function testUpdate()
    {
        //get existing
        $expectedArray = array(0 => array("title" => "Test form type", "tableName" => "_form_test_form_type", "sendToEmail" => 1, "emailToSend" => "test@test.com", "redirectArticleId" => 0, "redirectContentTypeId" => 0 ));  
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];        
        unset($formTypes[0]["id"]);             
        $idFiled = $this->dbAdapter->fetchOne("select id from formTypeFields where formTypeId = ?", $id);
        $this->assertEquals($formTypes,$expectedArray);
        
        //update existing    
        $values = json_decode('{"controller":"formtypes","action":"update","module":"cms","formTypeId":"'.$id.'","title":"Test form type1","sendToEmail":"1","newFormEmail":"test@test.com","redirectUrl":"","redirectCid":"0","redirectAid":"0","title_'.$idFiled.'":"test","formType_'.$idFiled.'":"text","defaultValues_'.$idFiled.'":"","showInList_'.$idFiled.'":"1","required_'.$idFiled.'":"1","titleField":"'.$idFiled.'","errorTitleFieldMsg":"You need to select one Title field!"}',true);
        $result = $this->formType->update($values);
        $this->assertTrue($result);
        
        //check if update successfull        
        $expectedArray = array(0 => array("title" => "Test form type1", "tableName" => "_form_test_form_type1", "sendToEmail" => 1, "emailToSend" => "test@test.com", "redirectArticleId" => 0, "redirectContentTypeId" => 0 ));  
        $formTypes = $this->formType->getFormTypesList();
        unset($formTypes[0]["id"]);
        $this->assertEquals($formTypes,$expectedArray);
        
        //update back to start values
        $values = json_decode('{"controller":"formtypes","action":"update","module":"cms","formTypeId":"'.$id.'","title":"Test form type","sendToEmail":"1","newFormEmail":"test@test.com","redirectUrl":"","redirectCid":"0","redirectAid":"0","title_'.$idFiled.'":"test","formType_'.$idFiled.'":"text","defaultValues_'.$idFiled.'":"","showInList_'.$idFiled.'":"1","required_'.$idFiled.'":"1","titleField":"'.$idFiled.'","errorTitleFieldMsg":"You need to select one Title field!"}',true);
        $result = $this->formType->update($values);
        $this->assertTrue($result);
        
        //check for start values
        $expectedArray = array(0 => array("title" => "Test form type", "tableName" => "_form_test_form_type", "sendToEmail" => 1, "emailToSend" => "test@test.com", "redirectArticleId" => 0, "redirectContentTypeId" => 0 ));  
        $formTypes = $this->formType->getFormTypesList();
        unset($formTypes[0]["id"]);
        $this->assertEquals($formTypes,$expectedArray);
    }
    
    public function testGetFormType()
    {
        //get existing
        $expectedArray = array(0 => array("title" => "Test form type", "tableName" => "_form_test_form_type", "sendToEmail" => 1, "emailToSend" => "test@test.com", "redirectArticleId" => 0, "redirectContentTypeId" => 0 ));  
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        unset($formTypes[0]["id"]);
        $idFiled = $this->dbAdapter->fetchOne("select id from formTypeFields where formTypeId = ?", $id);
        $this->assertEquals($formTypes,$expectedArray);
        
        $expectedArray = json_decode('{"id":'.$id.',"title":"Test form type","tableName":"_form_test_form_type","sendToEmail":1,"emailToSend":"test@test.com","redirectArticleId":0,"redirectContentTypeId":0,"fields":[{"id":'.$idFiled.',"formTypeId":'.$id.',"title":"test","fieldType":"text","defaultValue":"","showInList":1,"titleField":1,"required":1}],"listFields":[{"title":"test"}]}',true);
        $result = $this->formType->getFormType($id);
        $this->assertEquals($expectedArray,$result);
    }
    
    public function testGetSubmitionsList()
    {
        //get existing
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        unset($formTypes[0]["id"]);       

        $this->dbAdapter->insert("_form_test_form_type",array('defaultDate' => '2011-12-28 12:34:10', 'test' => 'test'));
        
        $expectedResult = array(array('test' => 'test', "defaultDate" => "2011-12-28 12:34:10"));
        $result = $this->formType->getSubmitionsList('_form_test_form_type',"_form_test_form_type.test,_form_test_form_type.id,_form_test_form_type.defaultDate",1,$id);
        unset($result[0]["id"]);
        
        $this->assertEquals($result,$expectedResult);       
                
    }
    
    public function testGetSubmitionData()
    {
        //get existing
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        unset($formTypes[0]["id"]); 
        //get id of row to see data
        $fid = $this->dbAdapter->fetchOne("select id from _form_test_form_type");

        $expectedResult = json_decode('{"content":{"defaultDate":"2011-12-28 12:34:10","test":"test"},"fields":[{"title":"test","fieldType":"text","defaultValue":"","showInList":1,"titleField":1,"required":1,"columnName":"test"}],"titleField":{"title":"test","columnName":"test"}}',true);
                
        $result = $this->formType->getSubmitionData($id,$fid);
        unset($result["content"]["id"]);
        unset($result["fields"][0]["id"]);
        unset($result["fields"][0]["formTypeId"]);
        $this->assertEquals($expectedResult,$result);

    }
    
    public function testGetContentList()
    {
        //get existing
        
        $formTypes = $this->formType->getFormTypesList();        
        $expectedResult = json_decode('{"Test form type::'.$formTypes[0]["id"].'":[{"articleTitle":"test","articleId":1}]}',true);
        
        $result = $this->formType->getContentList("te");
        
        $this->assertEquals($result,$expectedResult);
        
    }
    
    public function testDeleteColumn()
    {
        //get existing
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        unset($formTypes[0]["id"]);
        
        $idFiled = $this->dbAdapter->fetchOne("select id from formTypeFields where formTypeId = ?", $id);
        
        $result = $this->formType->deleteColumn($id,$idFiled);
        $this->assertEquals($result, array("status"=>true,"code"=>"deletecolumnOk"));
    }
    
    
    public function testDeleteSubmition()
    {
        //get existing
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        unset($formTypes[0]["id"]);
        
        $idFiled = $this->dbAdapter->fetchOne("select id from formTypeFields where formTypeId = ?", $id);
        
        $result = $this->formType->deleteSubmition($id,$idFiled);
        $this->assertEquals($result, true);                
    }
    
    public function testDeleteFormType()
    {
        $formTypes = $this->formType->getFormTypesList();
        $id = $formTypes[0]["id"];
        
        $expectedArray = array("status"=>true,"code"=>"ok_dell_contenttype");
        $delete = $this->formType->delete($id);
        $this->assertEquals($delete, $expectedArray);
        
        $formTypes = $this->formType->getFormTypesList();
        $this->assertEquals(0,count($formTypes));
    }   
    
}
?>