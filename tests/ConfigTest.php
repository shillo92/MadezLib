<?php
namespace Madez\Tests;

use Madez\ChildThemeSetup;
use Madez\Config;

/**
 * Class ConfigTest.
 *
 * @package Madez\Tests
 */
class ConfigTest extends \WP_UnitTestCase
{
    /**
     * @var Config
     */
    protected $config;

    function setUp()
    {
        parent::setUp();

        $this->config = ChildThemeSetup::getInstance()->getConfig();
    }


    public function testThemeRootUriReturnsValueSetInConfig()
    {
        $expected = 'http://'.WP_TESTS_DOMAIN.'/wp-content/themes/'.WP_DEFAULT_THEME;
        $actual = $this->config->getThemeRootUri();

        $this->assertEquals($expected, $actual);
    }
}