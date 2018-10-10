<?php
namespace Madez\Tests;

use Madez\ChildThemeSetup;

/**
 * Class ChildThemeSetupTest.
 *
 * @package Madez\Tests
 */
class ChildThemeSetupTest extends \WP_UnitTestCase
{
    /**
     * @var \ReflectionClass
     */
    protected $reflection;
    /**
     * @var ChildThemeSetup
     */
    protected $childThemeSetupObject;

    function setUp()
    {
        parent::setUp();

        $this->setupReflection();
    }

    protected function setupReflection()
    {
        $this->childThemeSetupObject = ChildThemeSetup::getInstance();
        $this->reflection = new \ReflectionClass(ChildThemeSetup::class);
    }

    public function testFaviconsMarkupDataIsReplacedCorrecty()
    {
        $method = $this->reflection->getMethod('prepareMarkupsDataForTheme');
        $method->setAccessible(true);

        $search = <<<HTML
<link rel="apple-touch-icon" sizes="180x180" href= "assets/favicon/build/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/build/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/build/favicon-16x16.png">
<link rel="manifest" href="assets/favicon/build/site.webmanifest">
<link rel="shortcut icon" href="assets/favicon/build/favicon.ico">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="assets/favicon/build/mstile-144x144.png">
<meta name="msapplication-config" content="assets/favicon/build/browserconfig.xml">
<meta name="theme-color" content="#ffffff">
HTML;

        $actual = $method->invoke($this->childThemeSetupObject, $search);

        $themeUri = $this->childThemeSetupObject->getConfig()->getThemeRootUri();

        $expected = <<<HTML
<link rel="apple-touch-icon" sizes="180x180" href="$themeUri/assets/favicon/build/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="$themeUri/assets/favicon/build/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="$themeUri/assets/favicon/build/favicon-16x16.png">
<link rel="manifest" href="$themeUri/assets/favicon/build/site.webmanifest">
<link rel="shortcut icon" href="$themeUri/assets/favicon/build/favicon.ico">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="$themeUri/assets/favicon/build/mstile-144x144.png">
<meta name="msapplication-config" content="$themeUri/assets/favicon/build/browserconfig.xml">
<meta name="theme-color" content="#ffffff">
HTML;

        $this->assertEquals($expected, $actual);
    }
}