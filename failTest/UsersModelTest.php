<?php
class UsersModelTest extends ControllerTestCase
{
    /**
     * Region model variable
     *
     * @var CMS_Model_Regions
     */
    protected $user;

    /**
     * Set up function is executed before any other function it init evreything neccessary
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->user = new Core_Model_Users();
    }

    public function testGetInfoByEmail()
    {
        $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":""}]',true);
        $result = $this->user->getInfoByEmail("dev");

        $this->assertEquals($result,$expectedResult);
    }

    public function testGetInfoById()
    {
        $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":""}]',true);
        $result = $this->user->getInfoById(30);

        $this->assertEquals($result,$expectedResult);
    }

    public function testGetUserInfoById()
    {
        $expectedResult = json_decode('{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":""}',true);
        $result = $this->user->getUserInfoById(30);

        $this->assertEquals($result,$expectedResult);
    }

    public function testUpdateUserInfo()
    {
       $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":""}]',true);
       $result = $this->user->getInfoById(30);

       $this->assertEquals($result,$expectedResult);

       $values = json_decode('{"fullName":"Root1","password":"d41d8cd98f00b204e9800998ecf8427e","address":"","city":"","zip":"","country":"AF","phone":"","tax":"","company":"","idnumber":"","id":"30"}',true);
       $update = $this->user->updateUserInfo($values,30);
       $this->assertTrue($update);

       $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root1","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":""}]',true);
       $result = $this->user->getInfoById(30);
       $this->assertEquals($result,$expectedResult);

       $values = json_decode('{"fullName":"Root","password":"d41d8cd98f00b204e9800998ecf8427e","address":"","city":"","zip":"","country":"AF","phone":"","tax":"","company":"","idnumber":"","id":"30"}',true);
       $update = $this->user->updateUserInfo($values,30);
       $this->assertTrue($update);
    }

    public function  testCreate()
    {
       $result = $this->user->getUsersList();
       $this->assertEquals(count($result),1);

       $values = json_decode('{"fullName":"guest","email":"guest","password":"e10adc3949ba59abbe56e057f20f883e","roleId":"7","address":"","city":"","zip":"","country":"RS","phone":"","tax":"","company":"","idnumber":""}',true);
       $result = $this->user->create($values);


       $result = $this->user->getUsersList();
       $this->assertEquals(count($result),2);
    }

    public function  testDelete()
    {
        $result = $this->user->getUsersList();
        $this->assertEquals(count($result),2);

        $guest = $this->user->getInfoByEmail("guest");
        $this->user->delete($guest[0]["id"],30);

        $result = $this->user->getUsersList();
        $this->assertEquals(count($result),1);
    }

    public function testGetUsersList()
    {
        $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":"","role":"root","root":1}]',true);
        $result = $this->user->getUsersList();

        $this->assertEquals($result,$expectedResult);
    }

    public function testGetUsersListByRole()
    {
        $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":"","role":"root"}]',true);
        $result = $this->user->getUsersListByRole(1);

        $this->assertEquals($result,$expectedResult);
    }

    public function testGetUsersListByRoles()
    {
        $expectedResult = json_decode('[{"id":30,"fbId":0,"email":"dev","password":"e10adc3949ba59abbe56e057f20f883e","admin":1,"fullName":"Root","roleId":1,"address":"","city":"","country":"AF","zip":"","phone":"","tax":"","company":"","idnumber":"","adminLang":"sr","avatar":"","role":"root"}]',true);
        $result = $this->user->getUsersListByRoles(1);

        $this->assertEquals($result,$expectedResult);
    }

    public function testGetAllCountries()
    {
        $expectedResult = json_decode('[{"ccode":"AF","country":"Afghanistan"},{"ccode":"AL","country":"Albania"},{"ccode":"DZ","country":"Algeria"},{"ccode":"AS","country":"American Samoa"},{"ccode":"AD","country":"Andorra"},{"ccode":"AO","country":"Angola"},{"ccode":"AI","country":"Anguilla"},{"ccode":"AQ","country":"Antarctica"},{"ccode":"AG","country":"Antigua and Barbuda"},{"ccode":"AR","country":"Argentina"},{"ccode":"AM","country":"Armenia"},{"ccode":"AW","country":"Aruba"},{"ccode":"AU","country":"Australia"},{"ccode":"AT","country":"Austria"},{"ccode":"AZ","country":"Azerbaijan"},{"ccode":"AX","country":"\u00c3\u2026land Islands"},{"ccode":"BS","country":"Bahamas"},{"ccode":"BH","country":"Bahrain"},{"ccode":"BD","country":"Bangladesh"},{"ccode":"BB","country":"Barbados"},{"ccode":"BY","country":"Belarus"},{"ccode":"BE","country":"Belgium"},{"ccode":"BZ","country":"Belize"},{"ccode":"BJ","country":"Benin"},{"ccode":"BM","country":"Bermuda"},{"ccode":"BT","country":"Bhutan"},{"ccode":"BO","country":"Bolivia"},{"ccode":"BA","country":"Bosnia and Herzegovina"},{"ccode":"BW","country":"Botswana"},{"ccode":"BV","country":"Bouvet Island"},{"ccode":"BR","country":"Brazil"},{"ccode":"IO","country":"British Indian Ocean Territory"},{"ccode":"BN","country":"Brunei Darussalam"},{"ccode":"BG","country":"Bulgaria"},{"ccode":"BF","country":"Burkina Faso"},{"ccode":"BI","country":"Burundi"},{"ccode":"KH","country":"Cambodia"},{"ccode":"CM","country":"Cameroon"},{"ccode":"CA","country":"Canada"},{"ccode":"CV","country":"Cape Verde"},{"ccode":"KY","country":"Cayman Islands"},{"ccode":"CI","country":"C\u00c3\u00b4te D\'Ivoire"},{"ccode":"CF","country":"Central African Republic"},{"ccode":"TD","country":"Chad"},{"ccode":"CL","country":"Chile"},{"ccode":"CN","country":"China"},{"ccode":"CX","country":"Christmas Island"},{"ccode":"CC","country":"Cocos (Keeling) Islands"},{"ccode":"CO","country":"Colombia"},{"ccode":"KM","country":"Comoros"},{"ccode":"CG","country":"Congo"},{"ccode":"CD","country":"Congo, The Democratic Republic of the"},{"ccode":"CK","country":"Cook Islands"},{"ccode":"CR","country":"Costa Rica"},{"ccode":"HR","country":"Croatia"},{"ccode":"CU","country":"Cuba"},{"ccode":"CY","country":"Cyprus"},{"ccode":"CZ","country":"Czech Republic"},{"ccode":"DK","country":"Denmark"},{"ccode":"DJ","country":"Djibouti"},{"ccode":"DM","country":"Dominica"},{"ccode":"DO","country":"Dominican Republic"},{"ccode":"EC","country":"Ecuador"},{"ccode":"EG","country":"Egypt"},{"ccode":"SV","country":"El Salvador"},{"ccode":"GQ","country":"Equatorial Guinea"},{"ccode":"ER","country":"Eritrea"},{"ccode":"EE","country":"Estonia"},{"ccode":"ET","country":"Ethiopia"},{"ccode":"FK","country":"Falkland Islands (Malvinas)"},{"ccode":"FO","country":"Faroe Islands"},{"ccode":"FJ","country":"Fiji"},{"ccode":"FI","country":"Finland"},{"ccode":"FR","country":"France"},{"ccode":"GF","country":"French Guiana"},{"ccode":"PF","country":"French Polynesia"},{"ccode":"TF","country":"French Southern Territories"},{"ccode":"GA","country":"Gabon"},{"ccode":"GM","country":"Gambia"},{"ccode":"GE","country":"Georgia"},{"ccode":"DE","country":"Germany"},{"ccode":"GH","country":"Ghana"},{"ccode":"GI","country":"Gibraltar"},{"ccode":"GR","country":"Greece"},{"ccode":"GL","country":"Greenland"},{"ccode":"GD","country":"Grenada"},{"ccode":"GP","country":"Guadeloupe"},{"ccode":"GU","country":"Guam"},{"ccode":"GT","country":"Guatemala"},{"ccode":"GG","country":"Guernsey"},{"ccode":"GN","country":"Guinea"},{"ccode":"GW","country":"Guinea-Bissau"},{"ccode":"GY","country":"Guyana"},{"ccode":"HT","country":"Haiti"},{"ccode":"HM","country":"Heard Island and McDonald Islands"},{"ccode":"VA","country":"Holy See (Vatican City State)"},{"ccode":"HN","country":"Honduras"},{"ccode":"HK","country":"Hong Kong"},{"ccode":"HU","country":"Hungary"},{"ccode":"IS","country":"Iceland"},{"ccode":"IN","country":"India"},{"ccode":"ID","country":"Indonesia"},{"ccode":"IR","country":"Iran, Islamic Republic of"},{"ccode":"IQ","country":"Iraq"},{"ccode":"IE","country":"Ireland"},{"ccode":"IM","country":"Isle of Man"},{"ccode":"IL","country":"Israel"},{"ccode":"IT","country":"Italy"},{"ccode":"JM","country":"Jamaica"},{"ccode":"JP","country":"Japan"},{"ccode":"JE","country":"Jersey"},{"ccode":"JO","country":"Jordan"},{"ccode":"KZ","country":"Kazakhstan"},{"ccode":"KE","country":"Kenya"},{"ccode":"KI","country":"Kiribati"},{"ccode":"KP","country":"Korea, Democratic People\'s Republic of"},{"ccode":"KR","country":"Korea, Republic of"},{"ccode":"KW","country":"Kuwait"},{"ccode":"KG","country":"Kyrgyzstan"},{"ccode":"LA","country":"Lao People\'s Democratic Republic"},{"ccode":"LV","country":"Latvia"},{"ccode":"LB","country":"Lebanon"},{"ccode":"LS","country":"Lesotho"},{"ccode":"LR","country":"Liberia"},{"ccode":"LY","country":"Libyan Arab Jamahiriya"},{"ccode":"LI","country":"Liechtenstein"},{"ccode":"LT","country":"Lithuania"},{"ccode":"LU","country":"Luxembourg"},{"ccode":"MO","country":"Macao"},{"ccode":"MK","country":"Macedonia, The Former Yugoslav Republic of"},{"ccode":"MG","country":"Madagascar"},{"ccode":"MW","country":"Malawi"},{"ccode":"MY","country":"Malaysia"},{"ccode":"MV","country":"Maldives"},{"ccode":"ML","country":"Mali"},{"ccode":"MT","country":"Malta"},{"ccode":"MH","country":"Marshall Islands"},{"ccode":"MQ","country":"Martinique"},{"ccode":"MR","country":"Mauritania"},{"ccode":"MU","country":"Mauritius"},{"ccode":"YT","country":"Mayotte"},{"ccode":"MX","country":"Mexico"},{"ccode":"FM","country":"Micronesia, Federated States of"},{"ccode":"MD","country":"Moldova, Republic of"},{"ccode":"MC","country":"Monaco"},{"ccode":"MN","country":"Mongolia"},{"ccode":"ME","country":"Montenegro"},{"ccode":"MS","country":"Montserrat"},{"ccode":"MA","country":"Morocco"},{"ccode":"MZ","country":"Mozambique"},{"ccode":"MM","country":"Myanmar"},{"ccode":"NA","country":"Namibia"},{"ccode":"NR","country":"Nauru"},{"ccode":"NP","country":"Nepal"},{"ccode":"NL","country":"Netherlands"},{"ccode":"AN","country":"Netherlands Antilles"},{"ccode":"NC","country":"New Caledonia"},{"ccode":"NZ","country":"New Zealand"},{"ccode":"NI","country":"Nicaragua"},{"ccode":"NE","country":"Niger"},{"ccode":"NG","country":"Nigeria"},{"ccode":"NU","country":"Niue"},{"ccode":"NF","country":"Norfolk Island"},{"ccode":"MP","country":"Northern Mariana Islands"},{"ccode":"NO","country":"Norway"},{"ccode":"OM","country":"Oman"},{"ccode":"PK","country":"Pakistan"},{"ccode":"PW","country":"Palau"},{"ccode":"PS","country":"Palestinian Territory, Occupied"},{"ccode":"PA","country":"Panama"},{"ccode":"PG","country":"Papua New Guinea"},{"ccode":"PY","country":"Paraguay"},{"ccode":"PE","country":"Peru"},{"ccode":"PH","country":"Philippines"},{"ccode":"PN","country":"Pitcairn"},{"ccode":"PL","country":"Poland"},{"ccode":"PT","country":"Portugal"},{"ccode":"PR","country":"Puerto Rico"},{"ccode":"QA","country":"Qatar"},{"ccode":"RE","country":"Reunion"},{"ccode":"RO","country":"Romania"},{"ccode":"RU","country":"Russian Federation"},{"ccode":"RW","country":"Rwanda"},{"ccode":"BL","country":"Saint Barth\u00c3\u00a9lemy"},{"ccode":"SH","country":"Saint Helena"},{"ccode":"KN","country":"Saint Kitts and Nevis"},{"ccode":"LC","country":"Saint Lucia"},{"ccode":"MF","country":"Saint Martin"},{"ccode":"PM","country":"Saint Pierre and Miquelon"},{"ccode":"VC","country":"Saint Vincent and the Grenadines"},{"ccode":"WS","country":"Samoa"},{"ccode":"SM","country":"San Marino"},{"ccode":"ST","country":"Sao Tome and Principe"},{"ccode":"SA","country":"Saudi Arabia"},{"ccode":"SN","country":"Senegal"},{"ccode":"RS","country":"Serbia"},{"ccode":"SC","country":"Seychelles"},{"ccode":"SL","country":"Sierra Leone"},{"ccode":"SG","country":"Singapore"},{"ccode":"SK","country":"Slovakia"},{"ccode":"SI","country":"Slovenia"},{"ccode":"SB","country":"Solomon Islands"},{"ccode":"SO","country":"Somalia"},{"ccode":"ZA","country":"South Africa"},{"ccode":"GS","country":"South Georgia and the South Sandwich Islands"},{"ccode":"ES","country":"Spain"},{"ccode":"LK","country":"Sri Lanka"},{"ccode":"SD","country":"Sudan"},{"ccode":"SR","country":"Suriname"},{"ccode":"SJ","country":"Svalbard and Jan Mayen"},{"ccode":"SZ","country":"Swaziland"},{"ccode":"SE","country":"Sweden"},{"ccode":"CH","country":"Switzerland"},{"ccode":"SY","country":"Syrian Arab Republic"},{"ccode":"TW","country":"Taiwan, Province Of China"},{"ccode":"TJ","country":"Tajikistan"},{"ccode":"TZ","country":"Tanzania, United Republic of"},{"ccode":"TH","country":"Thailand"},{"ccode":"TL","country":"Timor-Leste"},{"ccode":"TG","country":"Togo"},{"ccode":"TK","country":"Tokelau"},{"ccode":"TO","country":"Tonga"},{"ccode":"TT","country":"Trinidad and Tobago"},{"ccode":"TN","country":"Tunisia"},{"ccode":"TR","country":"Turkey"},{"ccode":"TM","country":"Turkmenistan"},{"ccode":"TC","country":"Turks and Caicos Islands"},{"ccode":"TV","country":"Tuvalu"},{"ccode":"UG","country":"Uganda"},{"ccode":"UA","country":"Ukraine"},{"ccode":"AE","country":"United Arab Emirates"},{"ccode":"GB","country":"United Kingdom"},{"ccode":"US","country":"United States"},{"ccode":"UM","country":"United States Minor Outlying Islands"},{"ccode":"UY","country":"Uruguay"},{"ccode":"UZ","country":"Uzbekistan"},{"ccode":"VU","country":"Vanuatu"},{"ccode":"VE","country":"Venezuela"},{"ccode":"VN","country":"Viet Nam"},{"ccode":"VG","country":"Virgin Islands, British"},{"ccode":"VI","country":"Virgin Islands, U.S."},{"ccode":"WF","country":"Wallis And Futuna"},{"ccode":"EH","country":"Western Sahara"},{"ccode":"YE","country":"Yemen"},{"ccode":"ZM","country":"Zambia"},{"ccode":"ZW","country":"Zimbabwe"}]',true);
        $result = $this->user->getAllCountries();

        $this->assertEquals($expectedResult,$result);
    }

    public function testIsExistEmail()
    {
        $result = $this->user->isExistEmail("dev");
        $this->assertTrue(!$result);
    }

    public function testGetAllLanguages()
    {
        $expectedResult = json_decode('[".","..","en","sr"]');
        $result = $this->user->getAllLanguages();

        $this->assertEquals($result,$expectedResult);
    }
}
?>