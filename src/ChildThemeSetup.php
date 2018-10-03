<?php
namespace Madez;

class ChildThemeSetup
{
    /**
     * @var ChildThemeSetup
     */
    private static $instance = null;

    /**
     * @return ChildThemeSetup
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor, use getInstance() instead.
     *
     * @see ChildThemeSetup::getInstance()
     */
    private function __construct()
    {
    }

    public function setup()
    {
        add_action( 'after_setup_theme', function() { $this->setupI18N(); } );
        add_action( 'wp_enqueue_scripts', function () { $this->setupStylesheet(); } );
        add_action( 'wp_enqueue_scripts', function () { $this->setupJavascripts(); } );
    }

    protected function setupJavascripts()
    {
        $name = 'child-script';
        $filename = '/js/build.min.js';

        WordpressFacilizer::loadScript($name, $filename, ['jquery']);
    }

    protected function setupStylesheet()
    {
        $parent_style = 'parent-style';
        $mainStylesheetFilename = Config::getMainStylesheetRelativeFilename();

        // Load parent stylesheet
        WordpressFacilizer::loadStylesheet($parent_style, Config::getParentThemeRootRelativeUri().'/style.css', [],
            FileRefreshOptions::NOACTION);

        // Load child stylesheet
        WordpressFacilizer::loadStylesheet('child-style', $mainStylesheetFilename, [$parent_style]);
    }

    /**
     * Setups the I18N and fires the theme_txtdomain_setup action.
     *
     * When finished setup, the 'theme_txtdomain_setup' action is fired and passed with one boolean argument which states whether or not
     * the text domain was loaded successfully.
     */
    protected function setupI18N()
    {
        do_action(
            'theme_txtdomain_setup',
            load_child_theme_textdomain( Config::getTextDomain(), Config::getThemeRootDirname() . '/languages' )
        );
    }
}