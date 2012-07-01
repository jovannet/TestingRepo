<?php
class RoleModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */

    protected $role;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->role = new Core_Model_Roles();
    }

    public function testGetAllRoles()
    {
        $expectedArray = array(array("title" => "root", "adminPanel" => 1, "userPanel" => 1, "rootRole" => 1, "guestRole" => 0),
                               4 => array("title" => "Guest", "adminPanel" => 0, "userPanel" => 0, "rootRole" => 0, "guestRole" => 1)
                               );
        $result = $this->role->getAllRoles();
        unset($result[1]);
        unset($result[2]);
        unset($result[3]);
        unset($result[0]["id"]);
        unset($result[4]["id"]);

        $this->assertEquals($result,$expectedArray);
    }
    public function testGetRoleInfo()
    {
        $result = $this->role->getRoleInfo(1);
        unset($result["id"]);
        $expectedArray = array("title" => "root", "adminPanel" => 1, "userPanel" => 1, "contentAccess" => 0, "rootRole" => 1, "guestRole" => 0);
        $this->assertEquals($result,$expectedArray);
    }

    public function testGetRoleMenuRules()
    {
        $result = $this->role->getRoleMenuRules(7);

        $expectedResult = json_decode('{"Admin menu":[{"title":"Informacije o sajtu","url":"\/websiteinfo","menuTitle":"Admin menu","resourceId":11,"rule":"deny"},{"title":"Jezici","url":"\/languages","menuTitle":"Admin menu","resourceId":12,"rule":false},{"title":"Tipovi sadr\u017eaja","url":"\/contenttypes","menuTitle":"Admin menu","resourceId":13,"rule":false},{"title":"Regioni","url":"\/regions","menuTitle":"Admin menu","resourceId":14,"rule":false},{"title":"Widgets","url":"\/widgets","menuTitle":"Admin menu","resourceId":15,"rule":false},{"title":"Settings","url":"\/websiteinfo","menuTitle":"Admin menu","resourceId":17,"rule":false},{"title":"Menus","url":"\/menus\/index","menuTitle":"Admin menu","resourceId":18,"rule":false},{"title":"Prava pristupa","url":"\/roles","menuTitle":"Admin menu","resourceId":55,"rule":false},{"title":"Users","url":"\/users","menuTitle":"Admin menu","resourceId":73,"rule":false},{"title":"Online shop","url":"\/onlineshop\/neworders","menuTitle":"Admin menu","resourceId":331,"rule":false},{"title":"Nove porud\u017ebine","url":"\/onlineshop\/neworders","menuTitle":"Admin menu","resourceId":332,"rule":false},{"title":"Realizovane porud\u017ebine","url":"\/onlineshop\/completeorders","menuTitle":"Admin menu","resourceId":337,"rule":false},{"title":"Kupci","url":"\/onlineshop\/clients","menuTitle":"Admin menu","resourceId":339,"rule":false},{"title":"Content","url":"#","menuTitle":"Admin menu","resourceId":551,"rule":"allow"},{"title":"Updater","url":"\/updater","menuTitle":"Admin menu","resourceId":715,"rule":false},{"title":"Pode\u0161avanja Shopa","url":"\/onlineshop\/settings","menuTitle":"Admin menu","resourceId":741,"rule":false},{"title":"Subscriptions","url":"\/subscriptions\/subscriptions\/settings","menuTitle":"Admin menu","resourceId":751,"rule":false},{"title":"Licenses","url":"\/licenses\/licenses\/list","menuTitle":"Admin menu","resourceId":765,"rule":false},{"title":"Sell License","url":"\/licenses\/licenses\/sell","menuTitle":"Admin menu","resourceId":818,"rule":false},{"title":"Themes","url":"\/themes\/index","menuTitle":"Admin menu","resourceId":821,"rule":false},{"title":"Newsletter","url":"\/newsletter\/index","menuTitle":"Admin menu","resourceId":922,"rule":false},{"title":"Newsletter Templates","url":"\/newsletter\/templates","menuTitle":"Admin menu","resourceId":923,"rule":false},{"title":"Opened tickets","url":"\/support\/support\/newtickets","menuTitle":"Admin menu","resourceId":938,"rule":false},{"title":"Support","url":"#","menuTitle":"Admin menu","resourceId":941,"rule":false},{"title":"Settings","url":"\/support\/support\/settings","menuTitle":"Admin menu","resourceId":942,"rule":false},{"title":"Departments","url":"\/support\/support\/listdepartments","menuTitle":"Admin menu","resourceId":943,"rule":false},{"title":"Create new ticket","url":"\/support\/support\/addticket","menuTitle":"Admin menu","resourceId":956,"rule":false},{"title":"Closed tickets","url":"\/support\/support\/closedtickets","menuTitle":"Admin menu","resourceId":961,"rule":false},{"title":"My tickets","url":"\/support\/support\/mytickets","menuTitle":"Admin menu","resourceId":963,"rule":false},{"title":"Forms","url":"#","menuTitle":"Admin menu","resourceId":969,"rule":"deny"},{"title":"Tipovi formi","url":"\/formtypes","menuTitle":"Admin menu","resourceId":972,"rule":"deny"},{"title":"Admin menu","url":"\/menus\/adminmenu","menuTitle":"Admin menu","resourceId":991,"rule":"deny"},{"title":"Comments","url":"#","menuTitle":"Admin menu","resourceId":996,"rule":"allow"},{"title":"Settings","url":"\/comments\/settings","menuTitle":"Admin menu","resourceId":998,"rule":"allow"},{"title":"Error pages","url":"\/error\/errorcontroll","menuTitle":"Admin menu","resourceId":1141,"rule":false},{"title":"Tipovi formi","url":"\/formtypes\/index","menuTitle":"Admin menu","resourceId":1325,"rule":"deny"}]}',true);

        foreach ($result as $k => $res)
        {
            foreach ($res as $key => $r)
            {
                unset($result[$k][$key]["resourceId"]);
            }
        }

        foreach ($expectedResult as $k => $expectedRes)
        {
            foreach ($expectedRes as $key => $r)
            {
                unset($expectedResult[$k][$key]["resourceId"]);
            }
        }
        $this->assertEquals($result,$result);
    }
/*
    public function testGetRoleFetarueRules()
    {
        $result = $this->role->getRoleFetarueRules(1);
        $expectedResult = json_decode('{"manage":[{"table":"","controller":"manage","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"list","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"save","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"addcategory","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"deletecategory","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"delete","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"edit","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"update","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"fulledit","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"fullupdate","useAcl":1,"rule":"allow"},{"table":"","controller":"manage","action":"reorder","useAcl":1,"rule":null},{"table":"","controller":"manage","action":"removewidget","useAcl":1,"rule":null},{"table":"","controller":"manage","action":"copy","useAcl":1,"rule":null},{"table":"","controller":"manage","action":"search","useAcl":1,"rule":null},{"table":"","controller":"manage","action":"contentlist","useAcl":1,"rule":null}],"contenttypes":[{"table":"","controller":"contenttypes","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"save","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"list","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"delete","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"edit","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"update","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"deleteColumn","useAcl":1,"rule":"allow"},{"table":"","controller":"contenttypes","action":"test","useAcl":1,"rule":null}],"error":[{"table":"","controller":"error","action":"error","useAcl":1,"rule":"allow"},{"table":"","controller":"error","action":"errorcontroll","useAcl":1,"rule":null}],"languages":[{"table":"","controller":"languages","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"languages","action":"disable","useAcl":1,"rule":"allow"},{"table":"","controller":"languages","action":"activate","useAcl":1,"rule":"allow"},{"table":"","controller":"languages","action":"setasdefault","useAcl":1,"rule":"allow"}],"login":[{"table":"","controller":"login","action":"process","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"logout","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"reminder","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"sendreminder","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"signup","useAcl":1,"rule":"allow"},{"table":"","controller":"login","action":"createaccount","useAcl":1,"rule":"allow"}],"regions":[{"table":"","controller":"regions","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"regions","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"regions","action":"delete","useAcl":1,"rule":"allow"}],"users":[{"table":"","controller":"users","action":"myinfo","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"updatemyinfo","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"save","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"edit","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"updateuserinfo","useAcl":1,"rule":"allow"},{"table":"","controller":"users","action":"delete","useAcl":1,"rule":"allow"}],"websiteinfo":[{"table":"","controller":"websiteinfo","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"websiteinfo","action":"update","useAcl":1,"rule":"allow"}],"widgets":[{"table":"","controller":"widgets","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"widgets","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"widgets","action":"delete","useAcl":1,"rule":"allow"},{"table":"","controller":"widgets","action":"getoptions","useAcl":1,"rule":"allow"},{"table":"","controller":"widgets","action":"changeoptions","useAcl":1,"rule":"allow"},{"table":"","controller":"widgets","action":"deleteconfirm","useAcl":1,"rule":null}],"roles":[{"table":"","controller":"roles","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"roles","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"roles","action":"edit","useAcl":1,"rule":"allow"},{"table":"","controller":"roles","action":"update","useAcl":1,"rule":"allow"},{"table":"","controller":"roles","action":"delete","useAcl":1,"rule":"allow"}],"menus":[{"table":"","controller":"menus","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"reorder","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"editlink","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"updatelink","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"getlinkinfo","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"createnewlink","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"removelink","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"add","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"delete","useAcl":1,"rule":"allow"},{"table":"","controller":"menus","action":"createnewlinkcontent","useAcl":1,"rule":null},{"table":"","controller":"menus","action":"adminmenu","useAcl":1,"rule":null}],"content":[{"table":"","controller":"content","action":"article","useAcl":1,"rule":"allow"},{"table":"","controller":"content","action":"index","useAcl":1,"rule":"allow"},{"table":"","controller":"content","action":"search","useAcl":1,"rule":"allow"},{"table":"","controller":"content","action":"submitform","useAcl":1,"rule":null},{"table":"","controller":"content","action":"submitcomment","useAcl":1,"rule":null},{"table":"","controller":"content","action":"submitajaxcomment","useAcl":1,"rule":null}],"shop":[{"table":"","controller":"shop","action":"login","useAcl":1,"rule":"allow"},{"table":"","controller":"shop","action":"shipping","useAcl":1,"rule":"allow"}],"onlineshop":[{"table":"","controller":"onlineshop","action":"neworders","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"payed","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"delivered","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"vieworder","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"completeorders","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"clients","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"listorders","useAcl":1,"rule":"allow"},{"table":"","controller":"onlineshop","action":"settings","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"savesettings","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientinfo","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientneworders","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientdeliveredorders","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientpayedinvoices","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientunpayedinvoices","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientpayedservices","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientunpayedservices","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"usercomments","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"leavecomment","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientopenedtickets","useAcl":1,"rule":null},{"table":"","controller":"onlineshop","action":"clientclosedtickets","useAcl":1,"rule":null}],"updater":[{"table":"","controller":"updater","action":"index","useAcl":1,"rule":null},{"table":"","controller":"updater","action":"update","useAcl":1,"rule":null}],"subscriptions":[{"table":"","controller":"subscriptions","action":"settings","useAcl":1,"rule":null},{"table":"","controller":"subscriptions","action":"savesettings","useAcl":1,"rule":null}],"licenses":[{"table":"","controller":"licenses","action":"list","useAcl":1,"rule":null},{"table":"","controller":"licenses","action":"sell","useAcl":1,"rule":null},{"table":"","controller":"licenses","action":"clientslist","useAcl":1,"rule":null},{"table":"","controller":"licenses","action":"sellpackage","useAcl":1,"rule":null}],"themes":[{"table":"","controller":"themes","action":"index","useAcl":1,"rule":null},{"table":"","controller":"themes","action":"add","useAcl":1,"rule":null},{"table":"","controller":"themes","action":"delete","useAcl":1,"rule":null},{"table":"","controller":"themes","action":"setasdefault","useAcl":1,"rule":null}],"newsletter":[{"table":"","controller":"newsletter","action":"index","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"templates","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"addtemplate","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"savetemplate","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"edittemplate","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"updatetemplate","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"deletetemplate","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"add","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"save","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"edit","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"update","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"delete","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"send","useAcl":1,"rule":null},{"table":"","controller":"newsletter","action":"sendemail","useAcl":1,"rule":null}],"support":[{"table":"","controller":"support","action":"newtickets","useAcl":1,"rule":null},{"table":"","controller":"support","action":"details","useAcl":1,"rule":null},{"table":"","controller":"support","action":"adddepartment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"listdepartments","useAcl":1,"rule":null},{"table":"","controller":"support","action":"savedepartment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"editdepartment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"updatedepartment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"removedepartment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"settings","useAcl":1,"rule":null},{"table":"","controller":"support","action":"updatesettings","useAcl":1,"rule":null},{"table":"","controller":"support","action":"updatesettings","useAcl":1,"rule":null},{"table":"","controller":"support","action":"leavecomment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"uploadattachment","useAcl":1,"rule":null},{"table":"","controller":"support","action":"replayticket","useAcl":1,"rule":null},{"table":"","controller":"support","action":"addticket","useAcl":1,"rule":null},{"table":"","controller":"support","action":"reademail","useAcl":1,"rule":null},{"table":"","controller":"support","action":"saveticket","useAcl":1,"rule":null},{"table":"","controller":"support","action":"closedtickets","useAcl":1,"rule":null},{"table":"","controller":"support","action":"mytickets","useAcl":1,"rule":null},{"table":"","controller":"support","action":"detailsuser","useAcl":1,"rule":null},{"table":"","controller":"support","action":"reopenticket","useAcl":1,"rule":null},{"table":"","controller":"support","action":"replayticketuser","useAcl":1,"rule":null},{"table":"","controller":"support","action":"updateticket","useAcl":1,"rule":null},{"table":"","controller":"support","action":"newticketsdepartment","useAcl":1,"rule":null}],"formtypes":[{"table":"","controller":"formtypes","action":"index","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"add","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"save","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"delete","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"edit","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"update","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"deletecolumn","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"list","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"deletesubmition","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"viewsubmition","useAcl":1,"rule":null},{"table":"","controller":"formtypes","action":"listcontent","useAcl":1,"rule":null}],"comments":[{"table":"","controller":"comments","action":"settings","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"savesettings","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"viewcomments","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"replayonarticle","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"generatereplayrow","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"replaytocomment","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"deletecomment","useAcl":1,"rule":null},{"table":"","controller":"comments","action":"setcommentstatus","useAcl":1,"rule":null}],"submitform":[{"table":"","controller":"submitform","action":"content","useAcl":1,"rule":null}]}',true);

        foreach ($result as $k => $res)
        {
            foreach ($res as $key => $r)
            {
                unset($result[$k][$key]["id"]);
                unset($result[$k][$key]["resourceId"]);
            }
        }
        $this->assertEquals($result,$expectedResult);
    }
 * */
    public function  testGetGuestRole()
    {
        $result = $this->role->getGuestRole();
        $this->assertEquals($result,5);
    }

    public function testGetRootRole()
    {
        $result = $this->role->getRootRole();
        $this->assertEquals($result,1);
    }
/*
    public function testupdateRole()
    {
        $val = $this->role->getRoleInfo(5);
        $expectedArrayOriginal = array("id" => 5,"title" => "Guest", "adminPanel" => 0, "userPanel" => 0, "contentAccess" => 0, "rootRole" => 0, "guestRole" => 1);

        $this->assertEquals($val,$expectedArrayOriginal);

        $values =array("controller"=>"roles","action"=>"update","module"=>"cms","title"=>"GuestBla","roleId"=>"7","allow"=>array("551","996", "998","145", "154", "227", "1077"));
        $result = $this->role->updateRole($values);

        $val1 = $this->role->getRoleInfo(7);
        $expectedArrayUpdate = array("id" => 7,"title" => "GuestBla", "adminPanel" => 0, "userPanel" => 0, "rootRole" => 0, "guestRole" => 1);
        $this->assertEquals($val1,$expectedArrayUpdate);

        $values =array("controller"=>"roles","action"=>"update","module"=>"cms","title"=>"Guest","roleId"=>"7","allow"=>array("551","996", "998","145", "154", "227", "1077"));
        $result = $this->role->updateRole($values);
    }
*/
    public function testAdminPanel()
    {
        $res = $this->role->adminPanel(1);
        $this->assertEquals(1,$res);

        $res = $this->role->adminPanel(5);
        $this->assertEquals(0,$res);
    }
/*
    public function testSaveAndDelete()
    {
        $result = $this->role->getAllRoles();
        $this->assertEquals(6,count($result));

        $this->assertEquals(1,$this->role->save("role"));

        $result = $this->role->getAllRoles();

        $this->assertEquals(6,count($result));

        $this->role->delete($result[2]["id"]);

        $result = $this->role->getAllRoles();
        $this->assertEquals(2,count($result));
    }
    //ne mogu da testiram dok ne budem testirao managecontent InitAciton potreban resourceId, getRegionsForContentType contentype,getAllRegions contenttype
*/
}
?>