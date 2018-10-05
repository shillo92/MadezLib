<?php
namespace Madez;

/**
 * Class ChildThemeSetup
 *
 * @package Madez
 */
class ChildThemeSetup
{
    /**
     * @var ChildThemeSetup
     */
    private static $instance = null;

    /**
     * @var Config
     */
    private $config;
    /**
     * @var WordpressFacilizer
     */
    private $facilizer;

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
        $this->config = $this->loadConfig();
    }

    /**
     * @return Config
     */
    protected function loadConfig() : Config
    {
        return (new Config());
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return WordpressFacilizer
     */
    public function getFacilizer(): WordpressFacilizer
    {
        return $this->facilizer;
    }

    /**
     * @param WordpressFacilizer $facilizer
     */
    public function setFacilizer(WordpressFacilizer $facilizer)
    {
        $this->facilizer = $facilizer;
    }

    public function setup()
    {
        add_action( 'after_setup_theme', function() { $this->setupI18N(); } );
        add_action( 'wp_enqueue_scripts', function () {
            $this->setupStylesheet();
            $this->setupJavascripts();
        });
        add_action( 'wp_head', function() { $this->setupFavicon(); } );
    }

    /**
     * Fires theme_favicon_setup with one flag argument, whether or not favicon setup was successful.
     */
    protected function setupFavicon()
    {
        $markupsFilename = $this->getConfig()->getFaviconMarkupsFilename();
        $flag = false;

        if (file_exists($markupsFilename)) {
            require($markupsFilename);
        }

        do_action('theme_favicon_setup', $flag);
    }

    protected function setupJavascripts()
    {
        $name = 'child-script';
        $filename = '/js/build.min.js';

        $this->getFacilizer()->loadScript($name, $filename, ['jquery']);
    }

    protected function setupStylesheet()
    {
        $config = $this->getConfig();
        $parent_style = 'parent-style';
        $mainStylesheetFilename = $config->getMainStylesheetRelativeFilename();
        $facilizer = $this->getFacilizer();

        // Load parent stylesheet
        $facilizer->loadStylesheet($parent_style, $config->getParentThemeRootRelativeUri().'/style.css', [],
            FileRefreshOptions::NOACTION);

        // Load child stylesheet
        $facilizer->loadStylesheet('child-style', $mainStylesheetFilename, [$parent_style]);
    }

    /**
     * Setups the I18N and fires the theme_txtdomain_setup action.
     *
     * When finished setup, the 'theme_txtdomain_setup' action is fired and passed with one boolean argument which states whether or not
     * the text domain was loaded successfully.
     */
    protected function setupI18N()
    {
        $config = $this->getConfig();
        do_action(
            'theme_txtdomain_setup',
            load_child_theme_textdomain( $config->getTextDomain(), $config->getThemeRootDirname() . '/languages' )
        );
    }
}