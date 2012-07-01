<?php
error_reporting(E_ALL | E_STRICT);

if (!defined('APPLICATION_PATH')) {
    define("APPLICATION_PATH", "/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application");
}

if (!defined('WEB_ROOT_PATH')) {
    define("WEB_ROOT_PATH", "/home/gitlaunc/public_html/cenkovic/GitLaunchpad/public_html/");
}

if (!defined('FB_APP_ID')) {
    define("FB_APP_ID", "126363994137616");
}

if (!defined('FB_APP_SECRET')) {
    define("FB_APP_SECRET", "39d5d2bca4e62f7afd084a20d8c919af");
}

if (!defined('APP_URL')) {
    define("APP_URL", "http://cenkovic.gitlaunchpad.com/GitLaunchpad/public_html/");
}

if (!defined('APP_ADMIN_URL')) {
    define("APP_ADMIN_URL", "http://test.dev.itplatforma.com/adminWide");
}


// Define application environment
/*  defined('APPLICATION_ENV')
  || define('APPLICATION_ENV',
  (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
  : 'production'));
 */
if (!defined('APPLICATION_ENV')) {
    define("APPLICATION_ENV", (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
}

// Adding library directory to the include path
set_include_path(implode(PATH_SEPARATOR, array(
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/library',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/subscriptions/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/licenses/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/twoco/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/support/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/facebook/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/cms/application/modules/cms/models',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/test/application/',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/test/library/',
            '/home/gitlaunc/public_html/cenkovic/GitLaunchpad/test/',
            get_include_path(),
        )));

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
$loader->registerNamespace('Zend_Auth');
$loader->registerNamespace('Cms');
$loader->registerNamespace('Subscriptions');
$loader->registerNamespace('Licenses');
$loader->registerNamespace('Facebook');


/** Zend_Application */
require_once 'Zend/Application.php';

require_once 'Core/Acl.php';
?>