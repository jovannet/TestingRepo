<?php

session_start();
//Get action to handle the whole process of installation

$action = $_POST['action'];
switch ($action) {

    case "database_check":
        $database_server = $_POST['database_server'];
        $database_username = $_POST['database_username'];
        $database_password = $_POST['database_password'];
        $database_name = $_POST['database_name'];

        //Proveravamo podatke koje je uneo korisnik.
        $link = @mysql_connect($database_server, $database_username, $database_password);
        if (!$link) {
            //Ako nije ok, mysql nam vraca neku gresku i mi to prosledjujemo korisniku
            echo "error::Error while connecting to database server. Check your parameters. (MySQL error: " . mysql_error() . ").";
            die();
        }

        //Ako je sve ok, proveravamo da li postoji baza koja nama treba
        $database = @mysql_select_db($database_name);
        if (!$database) {
            //Ako ne postoji varacamo korisniku gresku, da ta baza ne postoji i vratimo mysql gresku
            echo "error::Error while selecting database.Check if $database_name exist. (MySQL error: " . mysql_error() . ").";
            die();
        }
        echo "ok::Params OK";
        break;

    case "get_version":
        $files = getDirectoryList("./");
        rsort($files);
        $pattern = "/^install.[0-9]+\.[0-9]+\.[0-9]+\.zip$/";
        foreach ($files as $file) {
          $zipFileVersion = preg_match($pattern, $file);
          if ($zipFileVersion !== 0) {
            $fileExp = explode(".", $file);
            echo $fileExp[1] . "." . $fileExp[2] . "." . $fileExp[3];
            die();
          }
        }
        break;

    case "make_domain":
        echo $_SERVER['HTTP_HOST'];
        break;

    case "make_paths":
        $url = $_POST["url"];
        $domain = $_POST["domain"];
        $position = __FILE__;

        $url = explode("/", $url);
        unset($url[count($url) - 1]);
        $url = implode("/", $url);

        $position = explode("/", $position);
        unset($position[count($position) - 1]);
        $position = implode("/", $position);

        $domainExt = explode("/", $domain);
        unset($domainExt[0]);
        $domainExt = implode("/", $domainExt);

        $pathToDestract = str_replace($_SERVER['HTTP_HOST'], "||", $url);
        $pathToDestract = explode("||", $pathToDestract);
        unset($pathToDestract[0]);
        $public_path_st = str_replace($pathToDestract[1], "", $position);

        $public_path = $public_path_st . "/" . $domainExt;

        $public_path_st = explode("/", $public_path_st);
        unset($public_path_st[count($public_path_st) - 1]);
        $app_path = implode("/", $public_path_st);
        $app_path = $app_path . "/" . $domainExt;
        echo $public_path . "|||" . $app_path;

        break;

    case "shell_exec_test":
        $disabledFunctions = ini_get('disable_functions');
        if ($disabledFunctions != "") {
            $shellExecResult = strpos($disabledFunctions, "");
            if ($shellExecResult === false) {
                echo "ok::Shell_exec?";
                die();
            }
            echo "error::Shell_exec?";
        } else {
            echo "ok::Shell_exec?";
        }
        break;

    case "git_test":
        $gitPath = $_POST["git_path"];
        $gitTest = shell_exec($gitPath . " --version");
        if (is_null($gitTest) || $gitTest == "") {
            echo "error::Git?";
        } else {
            $gitVersion = explode(" ", $gitTest);
            echo "ok::" . $gitVersion[2];
        }
        break;

    case "zipArchive_test":
        //proveravamo da li postoji ZipArchive na serveru
        if (!class_exists("ZipArchive")) {
            //Ako ne postoji vratiti gresku
            echo "error::ZipArchive?";
            die();
        }
        echo "ok::ZipArchive?";
        break;


    case "phpDoc_test":
        $phpDocPath = $_POST["phpDoc_path"];
        $phpDoc = shell_exec($phpDocPath . " -h");
        if (is_null($phpDoc) && $phpDoc == "") {
            echo "warn::phpDocumentor?";
        } else {
            $phpDoc = explode("\n", $phpDoc);
            echo "ok::" . $phpDoc[0];
        }
        break;

    case "phpUnit_test":
        $phpUnitPath = $_POST["phpUnit_path"];
        $phpUnit = shell_exec($phpUnitPath . " --version");
        if (is_null($phpUnit) && $phpUnit == "") {
            echo "warn::phpUnit?";
        } else {
            echo "ok::" . $phpUnit;
        }
        break;



    //Kreiramo foldere u koje treba da instaliramo cms i sajt. Kopiramo i fajlove koji su potrebni za instalaciju
    case "create_files":
        //Uzimam potrebne parametre
        $www_path = $_POST['www_path'];
        $application_path = $_POST["application_path"];

        //Postavim string koji pamti gde cu da prekorpiam fajlove
        $new_dir = "/";
        //proveravam da li postoji putanja do javnog dela sajta i pravim je ako je nema
        if (file_exists($www_path)) {
            //proveravam da li je fajl ili dir
            if (is_dir($www_path)) {
                $new_dir = $www_path;
            } else {
                //pravim putanju ako ne postoji
                $dirCreated = @mkdir($www_path, 0755, true);
                if (!$dirCreated) {
                    echo "error::Can not make path to www folder!";
                    die();
                }
            }
        } else {
            //pravim putanju ako ne postoji
            $dirCreated = @mkdir($www_path, 0755, true);
            if (!$dirCreated) {
                echo "error::Can not make path to www folder!";
                die();
            }
        }

        //proveravam da li postoji putanja do aplikacionog dela sajta i pravim je ako je nema
        if (file_exists($application_path)) {
            //proveravam da li je fajl ili dir
            if (is_dir($application_path)) {
                
            } else {
                //pravim putanju ako ne postoji
                $dirCreated = @mkdir($application_path, 0755, true);
                if (!$dirCreated) {
                    echo "error::Can not make path to application folder!";
                    die();
                }
            }
        } else {
            //pravim putanju ako ne postoji
            $dirCreated = @mkdir($application_path, 0755, true);
            if (!$dirCreated) {
                echo "error::Can not make path to application folder!";
                die();
            }
        }

        //U sesiju spakujem putanju do direktorijuma sajta, posto ce mi trebati za kasnije
        $_SESSION['new_dir'] = $www_path;

        //kopiranje zip fajla
        $files = getDirectoryList("./");
        sort($files);
        $pattern = "/^install.[0-9]+\.[0-9]+\.[0-9]+\.zip$/";
        foreach ($files as $file) {
          $zipFileVersion = preg_match($pattern, $file);
          if ($zipFileVersion !== 0) {
            $fileExp = explode(".", $file);
            $zipVersion = $fileExp[1] . "." . $fileExp[2] . "." . $fileExp[3];
          }
        }
        //kopiramo install.zip i install.php
        $isCopied = @copy("./install." . $zipVersion . ".zip", $www_path . "install.zip");
        //proveravam da li je uspesno kopiran install.php fajl
        if (!$isCopied) {
            echo "error::Can not copy install.zip file!";
            die();
        }

        //Sve je ok, vracam status da je sve proslo kako treba
        echo "ok::Creating application folders";
        break;

    //Akcija koja nam brise instalacione fajlove nakon uspesne instalacije cms-a i sajta
    case "delete_files":

        //Uzimamo podatke potrebne za brisanje fajlova
        $new_dir = $_SESSION['new_dir'];
        $www_path = $_POST['www_path'];

        //Brisemo fajl database.sql sa servera
        $delete = @unlink($www_path . "database.sql");
        if (!$delete) {
            //Ako ga ne obrisemo onda vracamo error
            echo 'error::Error deleting database.sql failed.';
            die();
        }

        //Brisemo fajl install.zip sa servera
        $delete = @unlink($www_path . "install.zip");
        if (!$delete) {
            //Ako ga ne obrisemo vracamo error
            echo 'error::Error deleting install.zip failed.';
            die();
        }

        //Vratimo uspsan status nakon svega
        echo "ok::Removing installation files";
        break;


    //Akcija za rollback koja je razlicita od prethodnog rollbacka po tome sto ova ne brise fajlove, jer fajlovi nisu ni postavljeni na server
    //Ova akcija samo upise u nasu license tabelu koja je greska u pitanju kada dodje do greske prilikom instalacije
    case "rollback_status":

        //Preuzimamo parametre
        $license_key = $_POST['license_key'];
        $error_msg = $_POST['error_msg'];

        //Konektujemo se na bazu i izaberemo bazu
        $connection = mysql_connect("localhost", "human_license", "yvdv217w");
        $link = mysql_select_db("human_license");

        //Apdejtujemo bazu i upisujemo gresku koja se pojavila
        $query = mysql_query("UPDATE license SET `installMsg`='$error_msg' WHERE licenseKey='$license_key'");

        //Vracamo status
        echo "ok::Rollback successfully done!";

        mysql_close();

        break;

    case "git_auth":
        $username = $_POST["git_username"];
        $password = $_POST["git_password"];
        $auth = checkParamsForConnection($username, $password);
        if ($auth) {
            echo "ok::Username and password for git are confirmed!";
        } else {
            echo "error::Username or password are not correct";
        }
        break;


    case "pre_test":
        //Uzmemo site_path parametar koji je korisnik uneo
        $application_path = $_POST['application_path'];
        $www_path = $_POST['www_path'];

        //Uzmemo doc root servera i razbijemo kako bi uzeli putanju do cms-a
        //Proveriti da li su mi permisiije na doc_root-u dobar
        $permisions = substr(sprintf('%o', fileperms($application_path)), -4);
        if ($permisions != "0755" || $permisions != "0711") {
            $ch = chmod($application_path, 0755);
            if ($ch) {
                echo "warn::<b>Notice:</b> We changed some of your permissions in order to properly install GitLaunchpad.<br> Please review your server paths and permissions.";
                die();
            } else {
                echo "error::Error Permisions are not set correctly";
                die();
            }
        }
        break;




    //Krecemo sa unzipovanjem
    case "unzip_install":
        //Uzmemo site_path parametar koji je korisnik uneo
        $application_path = $_POST['application_path'];
        $www_path = $_POST['www_path'];

        //Ako ne postji folder cms na serveru, kriramo
        if (!is_dir($application_path . "cms")) {
            mkdir($application_path . "cms");
            mkdir($application_path . "cms/application");
            mkdir($application_path . "cms/library");
            $cms_path = $application_path . "cms";
        } else {
            //Ako postoji, onda kreiramo novi, samo sa dodatne 3 cifre
            $rand = rand(0, 999);
            mkdir($application_path . "cms" . $rand);
            mkdir($application_path . "cms" . $rand . "/application");
            mkdir($application_path . "cms" . $rand . "/library");
            $cms_path = $application_path . "cms" . $rand;
        }
        $zipFile = "";
        $files = getDirectoryList($www_path);
        foreach ($files as $file) {
            $a = strpos($file, "install.zip");
            if ($a !== false) {
                $zipFile = $file;
            }
        }
        
        //Raspakujem install.zip fajl
        $zip = new ZipArchive;
        $res = $zip->open($www_path . $zipFile);
        if ($res === TRUE) {
            $zip->extractTo($www_path);
            $zip->close();
        } else {
            //Ako nece da se raspakuje, vratimo gresku
            echo "error::Error while unziping file install.zip.";
        }

        //Rekurzivno iskopiramo ceo folder cms iz www direktorijuma u novi cms direktorijum
        recurse_copy($www_path . "/cms", $cms_path);
        //RecursiveCopy("cms/library", $cms_path."/library");
        //Uklonim stari cms iz www direktorijuma
        recursive_remove_directory($www_path . "/cms");
        //Rekurzivno prekopiramo sve iz public_html fajla iz zipa na mesto koje je trazio korisnik
        recurse_copy($www_path . "/public_html/", $www_path);
        //Brisemo folder sa fajlovima koji je dobijen iz zipa
        recursive_remove_directory($www_path . "/public_html/");
        //Sve proslo ok, vratim status
        echo "ok::Moving files and folders";
        break;


    //Radimo proveru baze i instalaciju iste
    case "install_database":
        //Preuzimamo parametre koje salje work.php
        $database_server = $_POST["database_server"];
        $database_username = $_POST["database_username"];
        $database_password = $_POST["database_password"];
        $database_name = $_POST["database_name"];
        $admin_username = $_POST['admin_username'];
        $admin_password = $_POST['admin_password'];
        $admin_name = $_POST['admin_name'];
        $admin_lastname = $_POST['admin_lastname'];
        $git_username = $_POST['git_username'];
        $git_password = $_POST['git_password'];
        $git_ssh = $_POST["git_ssh"];
        $unitStatus = $_POST["unitStatus"];
        $docStatus = $_POST["docStatus"];
        $www_path = $_POST["www_path"];

        $gitCommand = $_POST["git_command"];
        $phpUnitCommand = $_POST["phpunit_command"];
        $phpDocCommand = $_POST["phpDocumentor_command"];

        //Proveravamo podatke koje je uneo korisnik.
        $link = @mysql_connect($database_server, $database_username, $database_password);
        if (!$link) {
            //Ako nije ok, mysql nam vraca neku gresku i mi to prosledjujemo korisniku
            echo "error::Error while connecting to database server. Check your parameters. (MySQL error: " . mysql_error() . ").";
            die();
        }

        //Ako je sve ok, proveravamo da li postoji baza koja nama treba
        $database = @mysql_select_db($database_name);
        if (!$database) {
            //Ako ne postoji varacamo korisniku gresku, da ta baza ne postoji i vratimo mysql gresku
            echo "error::Error while selecting database.Check if $database_name exist. (MySQL error: " . mysql_error() . ").";
            die();
        }
        
        //Pakujemo niz sve tabele koje je potrebno proveriti da li postoje u bazi, ako neka postoji onda vracamo gresku
        $tables_array = array("categories", "contentTypeFields", "contentTypes", "countries", "email", "files", "languages", "menuLinks", "menus", "photos", "regionActions", "regions", "resourceCategories", "resources", "roles", "rules", "seoLink", "settings", "shopDeliveryMethods", "shopInvoices", "shopOrders", "shopPaymentMethods", "shoppingCart", "shopSettings", "stats", "themeExceptions", "themes", "users", "widgets", "_stranice");

        //Selektujemo sve tabele iz baze radi provere
        $tables = @mysql_list_tables($database_name);

        //Krecemo se kroz tabele iz baze
        while ($tables_exist = @mysql_fetch_array($tables)) {
            if (in_array($tables_exist[0], $tables_array)) {
                //Ako postoji neka koja nije potrebna onda vratimo error
                echo "error::Table '" . $tables_exist[0] . "' is already in database.";
                die();
            }
        }

        //Pokrecemo transakciju posto cemo da izvrsavamo kverije
        @mysql_query("START TRANSACTION");

        //Postavljamo statuse i error
        $error = "";
        $status = true;

        //Uzimamo ceo sadrzaj baze koju je potrebno instalirati i uzimamo svaki kveri posebno
        $file_content = @file_get_contents($www_path . "database.sql");
        $query_lines = explode(";", $file_content);

        //Krecemo se kroz svaki kveri i pokusavamo da ga odradimo
        foreach ($query_lines as $line) {
            $sql = $line . ";";
            $query = @mysql_query($sql);
            if (!query && mysql_error() != "Query was empty") {
                //Ako je doslo do greske u nekom kveriju, kazemo koji je error u pitanju i stavimo status na false
                $error = "error::Error while creating database. (MySQL error: " . mysql_error() . ")";
                $status = false;
            }
        }

        //Pokusamo da ubacimo korisnika u bazu, sa podacima koje je korisnik uneo
        $query_user = @mysql_query("INSERT INTO users (`email`, `password`, `admin`, `fullName`, `roleId`) VALUES ('$admin_username', '" . md5($admin_password) . "', '1', '" . $admin_name . " " . $admin_lastname . "', '1')");

        if (!$query_user) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while creating admin user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Ubacujemo settings tabelu
        $query_git_settings_u = @mysql_query("INSERT INTO `settings` (`id`, `param`, `title`, `paramValue`) VALUES (1, 'site_title', 'Website title', 'DEMO TEMA'),(2, 'keywords', 'Keywords', 'humanmade cms, template cms'),(3, 'description', 'Description', 'Demo theme for Humanmade CMS'),(4, 'footer_text', 'Footer text', '<p><span>&copy; Copyright 2011. All rights reserved. Powered by DEMO TEMA</span></p>'),(5, 'homePage', 'Home Page', '1686'),(6, 'facebook', 'Facebook', 'http://facebook.com'),(7, 'linkedin', 'Linked in', 'http://google.com'),(8, 'youtube', 'Youtube', 'http://youtube.com'),(9, 'twitter', 'Twitter', 'http://twitter.com'),(10, 'site_email', 'Site email', 'info@humanmade.rs'),(11, 'welcome_email', 'Welcome email', 'Po&scaron;tovani,  Dobrodo&scaron;li na Demo Platinu.  Va&scaron;em nalogu moÅ¾ete pristupiti sa adrese [link]. Va&scaron;e korisniÄ?ko ime je: [username]  Va&scaron;a lozinka je: [password]  SrdaÄ?no, Human CMS.<br />'),(12, 'googleEmail', 'Email', 'mr.nenad.marjanovic@gmail.com'),(13, 'googlePassword', 'Password', 'nenadskobalj'),(14, 'googleId', 'GA Profile Id', 'ga:45391214'),(15, 'registrationSuccess', 'registrationSuccess', '1686'),(16, 'reminder_email', 'CMS - Password reminder', 'ÄŒestitamo [ime]! <br /> Vasa lozinka je izmenjena. <br /> <br /> Login podaci:<br /> URL: [url] <br /> Username: [email] <br /> Password: [password]. <br />'),(17, 'reminder_subject', 'subject', 'CMS - Reminder'),(18, 'welcome_subject', 'subject', 'Welcome to Human CMS'),(19, 'github_user', 'username', ''),(23, 'github_pass', 'password', ''),(24, 'github_token', 'token', ''),(25, 'file_extension', 'files', 'xml,doc,docx,xls,xlsx,zip,rar,pdf,txt'),(26, 'picture_extension', 'pictuers', 'jpg,png,gif'),(27, 'github_ssh_key', 'ssh key', ''),(28, 'github_ssh_passphrase', 'git passphrase', ''),(29, 'phpDocStatus', 'phpDocumentor', '0'),(30, 'phpUnitStatus', 'phpUnit', '0'),(32,'revision_folder','revision folder',''),(33,'git_command','git execute commmand','git'),(34,'phpdoc_command','php documentation execute command',''),(35,'phpunit_command','phpUnit execute command',''),(38,'notrification_mail','Notrification email','".$admin_username."');");
        if (!$query_git_settings_u) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while inserting settings table. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Ubacujemo usera za git
        $query_git_settings_u = @mysql_query("UPDATE settings SET paramValue = '" . $git_username . "' WHERE id = '19'");
        if (!$query_git_settings_u) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Ubacujemo git pass
        $query_git_settings_p = mysql_query("UPDATE settings SET paramValue = '" . $git_password . "' WHERE id = '23'");
        if (!$query_git_settings_p) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git pass. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }        

        //Postavljamo status da li je instaliran phpDocumentor na serveru
        if($docStatus){
            $docStatus = 1;
        } else {
            $docStatus = 0;
        }
        $query_git_settings_doc = mysql_query("UPDATE settings SET paramValue = '" . $docStatus . "' WHERE id = '29'");
        if (!$query_git_settings_doc) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }


        //Postavljamo status da li je instaliran phpUnit na serveru
        if($unitStatus){
            $unitStatus = 1;
        } else {
            $unitStatus = 0;
        }
        $query_git_settings_unit = mysql_query("UPDATE settings SET paramValue = '" . $unitStatus . "' WHERE id = '30'");
        if (!$query_git_settings_unit) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }
        $revFolder = "rev";
        $query_git_settings_unit = mysql_query("UPDATE settings SET paramValue = '" . $revFolder . "' WHERE id = '32'");
        if (!$query_git_settings_unit) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }
        
        //Postavljamo komandu sa kojom ce se izvrsavati git
        $query_git_settings_doc = mysql_query("UPDATE settings SET paramValue = '" . $gitCommand . "' WHERE id = '33'");
        if (!$query_git_settings_doc) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Postavljamo komandu sa kojom ce se izvrsavati phpDocumentor
        $query_git_settings_doc = mysql_query("UPDATE settings SET paramValue = '" . $phpDocCommand . "' WHERE id = '34'");
        if (!$query_git_settings_doc) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Postavljamo komandu sa kojom ce se izvrsavati phpunit testovi
        $query_git_settings_doc = mysql_query("UPDATE settings SET paramValue = '" . $phpUnitCommand . "' WHERE id = '35'");
        if (!$query_git_settings_doc) {
            //Ako ni to nismo uspeli vracamo gresku da nije moguce
            $error = "error::Error while updating git user. (MySQL error: " . mysql_error() . ")";
            $status = false;
        }

        //Proverimo status ako je sve ok, komitujemo kverije
        if ($status) {
            echo "ok::Creating and populating database";
            mysql_query("COMMIT");
        } else {
            //Ako nije, onda radimo rollback
            mysql_query("ROLLBACK");
            echo $error;
            die();
        }

        break;

    //U ovom koraku apdejtujemo sve potrebne parametre koji su nam potrebni za funkcionisanje cms-a i sajta
    case "update_params":

        //Preuzimamo sa work.php sve potrebne parametre
        $database_server = $_POST["database_server"];
        $database_username = $_POST["database_username"];
        $database_password = $_POST["database_password"];
        $database_name = $_POST["database_name"];
        $www_path = $_POST['www_path'];
        $new_dir = $_POST['new_dir'];
        $domain = $_POST['domain'];
        $site_path = $_POST['site_path'];
        $application_path = $_POST['application_path'];
        $version = $_POST['version'];
        $license_key = $_POST['license_key'];
        $cms_path = $application_path . "cms/";
        
        
        if($www_path[strlen($www_path)-1] != "/"){
          $www_path .= "/";          
        }        
        if($site_path[strlen($site_path)-1] != "/"){
          $site_path .= "/";          
        }
        if($domain[strlen($domain)-1] != "/"){
          $domain .= "/";          
        }
        if($cms_path[strlen($cms_path)-1] != "/"){
          $cms_path .= "/";          
        }
        if($application_path[strlen($application_path)-1] != "/"){
          $application_path .= "/";          
        }
        if($domain[strlen($domain)-1] != "/"){
          $domain .= "/";          
        }
        if($www_path[strlen($www_path)-1] != "/"){
          $www_path .= "/";          
        }
        
        
        
        //Ovde sredjujemo applications.xml content
        //Uzmemo sadrzaj application.xml fajla i spakujemo u simple xml objekat
        
        chmod($application_path . "cms/application/configs/application.xml", 0755);
        $applicationXml = simplexml_load_file($application_path . "cms/application/configs/application.xml");
        if (!$applicationXml) {
            echo "error::Error opening application.xml file";
            die();
        }

        //Menjamo potrebne parametre u application.xml fajlu
        $applicationXml->production->version->attributes()->value = $version;
        $applicationXml->production->licenseKey->attributes()->value = $license_key;
        $applicationXml->production->resources->db->params->host->attributes()->value = $database_server;
        $applicationXml->production->resources->db->params->dbname->attributes()->value = $database_name;
        $applicationXml->production->resources->db->params->username->attributes()->value = $database_username;
        $applicationXml->production->resources->db->params->password->attributes()->value = $database_password;
        $applicationXml->production->resources->db->params->password->attributes()->value = $database_password;
        $applicationXml->production->webrootpath = $application_path;
                
        $applicationXmlStatus = $applicationXml->asXML($application_path . "cms/application/configs/application.xml");

        //Kada snimimo fajl proveravmo da li je sve ok, ako nije vracamo gresku
        if (!$applicationXmlStatus) {
            echo "error::Error saving application.xml file";
            die();
        }

        //config.php file3
        chmod($www_path . "config.php", 0755);
        $config_content = file_get_contents($www_path . "config.php");
        if (!$config_content) {
            echo "error::Error opening config.php file";
            die();
        }

        $doc_root = $_SERVER['DOCUMENT_ROOT'];
        
        //Menjamo podatke za config.php fajl
        $config_content = str_replace("[{APP_ADMIN_URL}]", "http://" . $domain . "adminWide", $config_content);
        $config_content = str_replace("[{APPLICATION_PATH}]", $cms_path . "application", $config_content);
        $config_content = str_replace("[{WEB_ROOT_PATH}]", $www_path, $config_content);
        $config_content = str_replace("[{APP_URL}]", "http://" . $domain, $config_content);
        $config_content = str_replace("[{ROOT_PATH}]", $application_path, $config_content);

        $status_config = file_put_contents($www_path . "config.php", $config_content);
        if (!$status_config) {
            echo "error::Error saving config.php file";
            die();
        }

        //index.php file
        chmod($www_path . "index.php", 0755);
        $index_content = file_get_contents($www_path . "index.php");
        if (!$index_content) {
            echo "error::Error opening index.php file";
            die();
        }

        $index_content = str_replace("[{LIB1}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB2}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB3}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB4}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB5}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB6}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB7}]", $cms_path, $index_content);
        $index_content = str_replace("[{LIB8}]", $cms_path, $index_content);

        $status_index = file_put_contents($www_path . "index.php", $index_content);
        if (!$status_index) {
            echo "error::Error saving index.php file";
            die();
        }

        
        $htaPath = $domain;
        $domain = str_replace("http://", "", $domain);
        $domain = str_replace("https://","",$domain);
        $domainArray = explode("/", $domain);
        if($domainArray !== false){
          unset($domainArray[0]);
          if(count($domainArray) > 0){
            $htaPath = implode("/", $domainArray);
            $htaPath = "/" . $htaPath . "/";
          } else {
            $htaPath = "/";
          }
        } else {
          $htaPath = "/";
        }
        
        //Renaming htaccess file, adding . infront of name
        chmod($www_path . "htaccess", 0755);
        $index_content1 = file_get_contents($www_path . "htaccess");
        if (!$index_content1) {
            echo "error::Error opening index.php file";
            die();
        }
        $index_content1 = str_replace("{[LIB1]}", $htaPath, $index_content1);

        $status_index1 = file_put_contents($www_path . "htaccess", $index_content1);
        if (!$status_index1) {
            echo "error::Error saving htaccess file";
            die();
        }
        $htaccess = copy($www_path . "htaccess", $www_path . ".htaccess");
        if (!$htaccess) {
            echo "error::Error renaming htaccess file";
            die();
        }
        echo "ok::Configuring application";

        break;



    //Rollback akcija a uklanjanje svih nepotrebnih stvari ako dodje do greske prilikom instalacije
    case "rollback":

        //Preuzimamo parametre koje nam salje work.php
        $domain = $_POST['domain'];
        $www_path = $_POST['www_path'];
        $application_path = $_POST["application_path"];
        $cms_path = $application_path . "cms/";
        $database_server = $_POST['database_server'];
        $database_user = $_POST['database_user'];
        $database_password = $_POST['database_password'];
        $database_name = $_POST['database_name'];

        //Proveravamo promenljive oko baze da li su poslati, ako su poslati znaci da je baza instalirana ili je neuspesno instalrina, pa onda moramo da pobrisemo sve tabele koje smo kreirali
        if ($database_server != "" && $database_user != "" && $database_password != "" && $database_name != "") {

            mysql_connect($database_server, $database_user, $database_password);
            mysql_select_db($database_name);

            //Niz svih tabela koje smo pokusali da instaliramo, tako da cemo da proverimo da li je neka od ovih jos uvek u bazi i da uklonimo
            $tables_array = array("categories", "contentTypeFields", "contentTypes", "countries", "email", "files", "languages", "menuLinks", "menus", "photos", "regionActions", "regions", "resourceCategories", "resources", "roles", "rules", "seoLink", "settings", "shopDeliveryMethods", "shopInvoices", "shopOrders", "shopPaymentMethods", "shoppingCart", "shopSettings", "stats", "themeExceptions", "themes", "users", "widgets", "_stranice");

            //Prolazimo kroz niz tabela i proveravamo da li postoje
            foreach ($tables_array as $key => $value) {
                //Ako postoji neka tabela, onda je brisemo
                if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . $value . "'"))) {
                    mysql_query("DROP TABLE `" . $value . "`");
                }
            }
        }

        //Proverimo za svaki slucaj da li postoji folder cms
        if (is_dir($cms_path)) {
            recursive_remove_directory($cms_path);
        }

        //Uklanjamo folder adminWide ako postoji
        if (is_dir($www_path . "adminThemes")) {
            recursive_remove_directory($www_path . "adminThemes");
        }

        //Uklanjamo folder themes ako postoji
        if (is_dir($www_path . "themes")) {
            recursive_remove_directory($www_path . "themes");
        }

        //Uklanjamo fajl config.php ako postoji
        if (is_file($www_path . "config.php")) {
            unlink($www_path . "config.php");
        }

        //Uklanjamo fajl index.php ako postoji
        if (is_file($www_path . "index.php")) {
            unlink($www_path . "index.php");
        }

        //Uklanjamo fajl .htaccess ako postoji
        if (is_file($www_path . ".htaccess")) {
            unlink($www_path . ".htaccess");
        }
        ////Uklanjamo fajl htaccess ako postoji
        if (is_file("htaccess")) {
            unlink($www_path . "htaccess");
        }

        //Uklanjamo fajl install.php ako postoji
        if (is_file($www_path . "install.php")) {
            unlink($www_path . "install.php");
        }

        //Uklanjamo install.zip fajl ako postoji
        if (is_file($www_path . "install.zip")) {
            unlink($www_path . "install.zip");
        }

        //Uklanjamo fajl database.sql ako postoji
        if (is_file($www_path . "database.sql")) {
            unlink($www_path . "database.sql");
        }

        //Uklanjamo fajl __avatars ako postoji
        if (is_dir($www_path . "__avatars")) {
            recursive_remove_directory($www_path . "__avatars");
        }

        //Uklanjamo fajl __uploads ako postoji
        if (is_dir($www_path . "__uploads")) {
            recursive_remove_directory($www_path . "__uploads");
        }

        //Uklanjamo fajl _projects ako postoji
        if (is_dir($www_path . "_projects")) {
            recursive_remove_directory($www_path . "_projects");
        }

        //Uklanjamo fajl dropAndCreateDatabase.sql ako postoji
        if (is_file($www_path . "dropAndCreateDatabase.sql")) {
            unlink($www_path . "dropAndCreateDatabase.sql");
        }

        //Uklanjamo fajl enter.sh ako postoji
        if (is_file($www_path . "enter.sh")) {
            unlink($www_path . "enter.sh");
        }

        //Uklanjamo fajl removeAndRestore.sh ako postoji
        if (is_file($www_path . "removeAndRestore.sh")) {
            unlink($www_path . "removeAndRestore.sh");
        }

        //Uklanjamo fajl script.sh ako postoji
        if (is_file($www_path . "script.sh")) {
            unlink($www_path . "script.sh");
        }

        //Uklanjamo fajl sshPosition.sh ako postoji
        if (is_file($www_path . "sshPosition.sh")) {
            unlink($www_path . "sshPosition.sh");
        }

        //Uklanjamo fajl unit.sh ako postoji
        if (is_file($www_path . "unit.sh")) {
            unlink($www_path . "unit.sh");
        }

        //Uklanjamo fajl update.sql ako postoji
        if (is_file($www_path . "update.sql")) {
            unlink($www_path . "update.sql");
        }

        //Vracamo da je rollback prosao kako treba
        echo "ok::Rollback successfully done!";

        break;
}

function checkParamsForConnection($username, $password) {       
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, "https://api.github.com/user/keys");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_USERPWD, "$username:$password");
    if($options["inputPost"])
      curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($options["inputPost"]));
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($c);
    curl_close($c);
    $response = json_decode($response,true);
    if(isset($response["message"])){        
      $autenticated = false;
    } else {
      $autenticated = true;
    }
    return $autenticated;
}


//uzimanje fajlova iz direktorijuma za zip
function getDirectoryList($directory) {

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // open directory and walk through the filenames
    while ($file = readdir($handler)) {

        // if file isn't this directory or its parent, add it to the results
        if ($file != "." && $file != "..") {
            $results[] = $file;
        }
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;
}

//stampa permisije fajla
function file_perms($file, $octal = false) {
    if (!file_exists($file))
        return false;

    $perms = fileperms($file);

    $cut = $octal ? 2 : 3;

    return substr(decoct($perms), $cut);
}

//Funckija koja brise direktorijum i u rekurzivnom prolazu brise i sve poddirektoprijume i fajlove unutar njega
function recursive_remove_directory($directory, $empty = FALSE) {
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }

    if (!file_exists($directory) || !is_dir($directory)) {
        return FALSE;
    } else if (is_readable($directory)) {
        $handle = opendir($directory);

        while (FALSE !== ($item = readdir($handle))) {
            if ($item != '.' && $item != '..') {
                $path = $directory . '/' . $item;
                if (is_dir($path)) {
                    recursive_remove_directory($path);
                } else {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        if ($empty == FALSE) {
            if (!rmdir($directory)) {
                return FALSE;
            }
        }
    }
    return TRUE;
}

//Funckija koja kopira ceo folder i koja rekurzivno proverava i kopira sve podfoldere i fajlove unutar njega
function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                @copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    @closedir($dir);
}

?>