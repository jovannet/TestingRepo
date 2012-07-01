<?php
class LanguageModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Language
     */
    protected $language;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->language = new Core_Model_Language();
    }
    //tested method getActiveLanguages()
    public function testGetActiveLanguages()
    {
       $activeLanguages = $this->language->getActiveLanguages();

       $expectedArray = array(array("id"=>1, "code" => "en", "title" => "English" , "isDefault" => 1, "default_locale" => "en_US"));
       $this->assertEquals($activeLanguages, $expectedArray);
    }
    //tested method getDefaultLanguage
    public function testGetDefaultLanguage()
    {
        $defLang = $this->language->getDefaultLanguage();
        $expectedString = "en";

        $this->assertEquals($expectedString,$defLang);
    }
    //tested method getInactiveLanguages
  /*  public function testGetInactiveLanguages()
    {
        $inactiveLang = $this->language->getInactiveLanguages();

        $expectedArray = json_decode('[{"id":63,"code":"zh-hant","title":"Chinese (Traditional)","isDefault":0,"default_locale":"zh_TW"},{"id":46,"code":"ro","title":"Romanian","isDefault":0,"default_locale":"ro_RO"},{"id":45,"code":"qu","title":"Quechua","isDefault":0,"default_locale":""},{"id":44,"code":"pt-br","title":"Portuguese, Brazil","isDefault":0,"default_locale":"pt_BR"},{"id":43,"code":"pt-pt","title":"Portuguese, Portugal","isDefault":0,"default_locale":"pt_PT"},{"id":42,"code":"pl","title":"Polish","isDefault":0,"default_locale":"pl_PL"},{"id":41,"code":"pa","title":"Punjabi","isDefault":0,"default_locale":""},{"id":40,"code":"nb","title":"Norwegian Bokm\u00c3\u00a5l","isDefault":0,"default_locale":"nb_NO"},{"id":39,"code":"nl","title":"Dutch","isDefault":0,"default_locale":"nl_NL"},{"id":38,"code":"ne","title":"Nepali","isDefault":0,"default_locale":""},{"id":37,"code":"mn","title":"Mongolian","isDefault":0,"default_locale":""},{"id":36,"code":"mo","title":"Moldavian","isDefault":0,"default_locale":""},{"id":35,"code":"mt","title":"Maltese","isDefault":0,"default_locale":""},{"id":34,"code":"mk","title":"Macedonian","isDefault":0,"default_locale":"mk_MK"},{"id":47,"code":"ru","title":"Russian","isDefault":0,"default_locale":"ru_RU"},{"id":48,"code":"sl","title":"Slovenian","isDefault":0,"default_locale":"sl_SI"},{"id":62,"code":"zu","title":"Zulu","isDefault":0,"default_locale":""},{"id":61,"code":"zh-hans","title":"Chinese (Simplified)","isDefault":0,"default_locale":"zh_CN"},{"id":60,"code":"yi","title":"Yiddish","isDefault":0,"default_locale":""},{"id":59,"code":"vi","title":"Vietnamese","isDefault":0,"default_locale":"vi"},{"id":58,"code":"uz","title":"Uzbek","isDefault":0,"default_locale":"uz_UZ"},{"id":57,"code":"ur","title":"Urdu","isDefault":0,"default_locale":""},{"id":56,"code":"uk","title":"Ukrainian","isDefault":0,"default_locale":"uk_UA"},{"id":55,"code":"tr","title":"Turkish","isDefault":0,"default_locale":"tr"},{"id":54,"code":"th","title":"Thai","isDefault":0,"default_locale":"th"},{"id":53,"code":"ta","title":"Tamil","isDefault":0,"default_locale":""},{"id":52,"code":"sv","title":"Swedish","isDefault":0,"default_locale":"sv_SE"},{"id":50,"code":"sq","title":"Albanian","isDefault":0,"default_locale":""},{"id":49,"code":"so","title":"Somali","isDefault":0,"default_locale":""},{"id":33,"code":"lt","title":"Lithuanian","isDefault":0,"default_locale":"lt"},{"id":32,"code":"lv","title":"Latvian","isDefault":0,"default_locale":"lv"},{"id":31,"code":"la","title":"Latin","isDefault":0,"default_locale":""},{"id":15,"code":"et","title":"Estonian","isDefault":0,"default_locale":"et"},{"id":14,"code":"eo","title":"Esperanto","isDefault":0,"default_locale":"eo"},{"id":13,"code":"el","title":"Greek","isDefault":0,"default_locale":"el"},{"id":12,"code":"da","title":"Danish","isDefault":0,"default_locale":"da_DK"},{"id":11,"code":"cy","title":"Welsh","isDefault":0,"default_locale":"cy"},{"id":10,"code":"sla","title":"Slavic","isDefault":0,"default_locale":""},{"id":9,"code":"cs","title":"Czech","isDefault":0,"default_locale":"cs_CZ"},{"id":8,"code":"ca","title":"Catalan","isDefault":0,"default_locale":"ca"},{"id":7,"code":"bg","title":"Bulgarian","isDefault":0,"default_locale":"bg_BG"},{"id":6,"code":"bs","title":"Bosnian","isDefault":0,"default_locale":""},{"id":5,"code":"ar","title":"Arabic","isDefault":0,"default_locale":"ar"},{"id":4,"code":"fr","title":"French","isDefault":0,"default_locale":"fr_FR"},{"id":3,"code":"de","title":"German","isDefault":0,"default_locale":"de_DE"},{"id":16,"code":"eu","title":"Basque","isDefault":0,"default_locale":"eu"},{"id":17,"code":"fa","title":"Persian","isDefault":0,"default_locale":"fa_IR"},{"id":30,"code":"ku","title":"Kurdish","isDefault":0,"default_locale":"ku"},{"id":29,"code":"ko","title":"Korean","isDefault":0,"default_locale":"ko_KR"},{"id":28,"code":"ja","title":"Japanese","isDefault":0,"default_locale":"ja"},{"id":27,"code":"it","title":"Italian","isDefault":0,"default_locale":"it"},{"id":26,"code":"is","title":"Icelandic","isDefault":0,"default_locale":"is_IS"},{"id":25,"code":"id","title":"Indonesian","isDefault":0,"default_locale":"id_ID"},{"id":24,"code":"hy","title":"Armenian","isDefault":0,"default_locale":""},{"id":23,"code":"hu","title":"Hungarian","isDefault":0,"default_locale":"hu_HU"},{"id":22,"code":"hr","title":"Croatian","isDefault":0,"default_locale":"hr"},{"id":21,"code":"hi","title":"Hindi","isDefault":0,"default_locale":""},{"id":20,"code":"he","title":"Hebrew","isDefault":0,"default_locale":"he_IL"},{"id":19,"code":"ga","title":"Irish","isDefault":0,"default_locale":""},{"id":18,"code":"fi","title":"Finnish","isDefault":0,"default_locale":"fi_FI"},{"id":2,"code":"es","title":"Spanish","isDefault":0,"default_locale":"es_ES"}]', true);

        $this->assertEquals($expectedArray,$inactiveLang);
    }
    //test activate($id) laguages
    public function testActivateLanguage()
    {
        $activeLanguages = $this->language->getActiveLanguages();
        $this->assertEquals(count($activeLanguages),2);

        $result = $this->language->activate(14);
        $this->assertEquals(1,$result);

        $activeLanguages = $this->language->getActiveLanguages();
        $this->assertEquals(count($activeLanguages),3);
    }
     //test setAsDefault($id)
    public function testSetAsDefault()
    {
        $defLang = $this->language->getDefaultLanguage();
        $expectedString = "en";
        $this->assertEquals($expectedString,$defLang);

        $result = $this->language->setAsDefault(51);
        $this->assertEquals(1,$result);

        $defLang = $this->language->getDefaultLanguage();
        $expectedString = "sr";
        $this->assertEquals($expectedString,$defLang);

        $result = $this->language->setAsDefault(1);
        $this->assertEquals(1,$result);
        $defLang = $this->language->getDefaultLanguage();
        $expectedString = "en";
        $this->assertEquals($expectedString,$defLang);

    }
    //test disable($id), languages getActiveLanguages()
    public function testDisableLanguage()
    {
        $activeLanguages = $this->language->getActiveLanguages();
        $this->assertEquals(count($activeLanguages),3);

        $result = $this->language->disable(14);
        $this->assertEquals(1,$result);

        $activeLanguages = $this->language->getActiveLanguages();
        $this->assertEquals(count($activeLanguages),2);
    }
   */
}
?>