<?php
class WebsiteinfoModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */
    protected $websiteInfo;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->websiteInfo = new Core_Model_Websiteinfo();
    }

    public function testGetWebsiteInfo()
    {
        $expected = json_decode('[{"id":1,"param":"site_title","title":"Website title","paramValue":"Humanmade.com"},{"id":2,"param":"keywords","title":"Keywords","paramValue":"humanmade cms, template cms"},{"id":3,"param":"description","title":"Description","paramValue":"Demo theme for Humanmade CMS"},{"id":4,"param":"footer_text","title":"Footer text","paramValue":"© Copyright 2011. All rights reserved. Powered by Platforma<\/a><\/span><\/p>"},{"id":5,"param":"homePage","title":"Home Page","paramValue":"1029"},{"id":6,"param":"facebook","title":"Facebook","paramValue":"http:\/\/www.facebook.com"},{"id":7,"param":"linkedin","title":"Linked in","paramValue":"http:\/\/linkedin.com"},{"id":8,"param":"youtube","title":"Youtube","paramValue":"http:\/\/youtube.com"},{"id":9,"param":"twitter","title":"Twitter","paramValue":"http:\/\/twitter.com"},{"id":10,"param":"site_email","title":"Site email","paramValue":"info@humanmade.rs"},{"id":11,"param":"welcome_email","title":"Welcome email","paramValue":"Poštovani, Dobrodošli u Tropic Trade online shop. Vašem nalogu mo\u017eete pristupiti sa adrese [link]. Vaše korisni\u010dko ime je: [username] Vaša lozinka je: [password] Srda\u010dno, Tropic Trade doo aa"},{"id":12,"param":"googleEmail","title":"Email","paramValue":""},{"id":13,"param":"googlePassword","title":"Password","paramValue":""},{"id":14,"param":"googleId","title":"GA Profile Id","paramValue":""}]',true);
        unset($expected[10]);
        unset($expected[3]);

        $result = $this->websiteInfo->getWebsiteInfo();
        unset($result[10]);
        unset($result[3]);
        $this->assertEquals($expected,$result);
    }

    public  function  testUpdateSettings()
    {
        $this->testGetWebsiteInfo();

        $values = json_decode('{"site_title":"Humanmade.com1","site_email":"info@humanmade.rs","keywords":"humanmade cms, template cms","description":"Demo theme for Humanmade CMS","footer_text":"© Copyright 2011. All rights reserved. Powered by Platforma<\/a><\/span><\/p>","facebook":"http:\/\/www.facebook.com","linkedin":"http:\/\/linkedin.com","youtube":"http:\/\/youtube.com","twitter":"http:\/\/twitter.com","welcome_email":"Poštovani, Dobrodošli u Tropic Trade online shop. Vašem nalogu mo\u017eete pristupiti sa adrese [link]. Vaše korisni\u010dko ime je: [username] Vaša lozinka je: [password] Srda\u010dno, Tropic Trade doo aa","googleEmail":"","googlePassword":"","googleId":""}');
        $result = $this->websiteInfo->updateSettings($values);
        $this->assertTrue($result);

        $expected = json_decode('[{"id":1,"param":"site_title","title":"Website title","paramValue":"Humanmade.com1"},{"id":2,"param":"keywords","title":"Keywords","paramValue":"humanmade cms, template cms"},{"id":3,"param":"description","title":"Description","paramValue":"Demo theme for Humanmade CMS"},{"id":4,"param":"footer_text","title":"Footer text","paramValue":"© Copyright 2011. All rights reserved. Powered by Platforma<\/a><\/span><\/p>"},{"id":5,"param":"homePage","title":"Home Page","paramValue":"1029"},{"id":6,"param":"facebook","title":"Facebook","paramValue":"http:\/\/www.facebook.com"},{"id":7,"param":"linkedin","title":"Linked in","paramValue":"http:\/\/linkedin.com"},{"id":8,"param":"youtube","title":"Youtube","paramValue":"http:\/\/youtube.com"},{"id":9,"param":"twitter","title":"Twitter","paramValue":"http:\/\/twitter.com"},{"id":10,"param":"site_email","title":"Site email","paramValue":"info@humanmade.rs"},{"id":11,"param":"welcome_email","title":"Welcome email","paramValue":"Poštovani, Dobrodošli u Tropic Trade online shop. Vašem nalogu mo\u017eete pristupiti sa adrese [link]. Vaše korisni\u010dko ime je: [username] Vaša lozinka je: [password] Srda\u010dno, Tropic Trade doo aa"},{"id":12,"param":"googleEmail","title":"Email","paramValue":""},{"id":13,"param":"googlePassword","title":"Password","paramValue":""},{"id":14,"param":"googleId","title":"GA Profile Id","paramValue":""}]',true);
        unset($expected[10]);
        unset($expected[3]);

        $result = $this->websiteInfo->getWebsiteInfo();
        unset($result[10]);
        unset($result[3]);
        $this->assertEquals($expected,$result);

        $values = json_decode('{"site_title":"Humanmade.com","site_email":"info@humanmade.rs","keywords":"humanmade cms, template cms","description":"Demo theme for Humanmade CMS","footer_text":"© Copyright 2011. All rights reserved. Powered by Platforma<\/a><\/span><\/p>","facebook":"http:\/\/www.facebook.com","linkedin":"http:\/\/linkedin.com","youtube":"http:\/\/youtube.com","twitter":"http:\/\/twitter.com","welcome_email":"Poštovani, Dobrodošli u Tropic Trade online shop. Vašem nalogu mo\u017eete pristupiti sa adrese [link]. Vaše korisni\u010dko ime je: [username] Vaša lozinka je: [password] Srda\u010dno, Tropic Trade doo aa","googleEmail":"","googlePassword":"","googleId":""}');
        $result = $this->websiteInfo->updateSettings($values);
        $this->assertTrue($result);

        $this->testGetWebsiteInfo();
    }
   //ne moze da se izvrsi mora da ima homepage setovan.
   /*public function testHomepageOpitons()
    {

    }*/
}
?>