<?php
namespace Madez;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ChildThemeSetup
 *
 * @package Madez
 */
class ChildThemeSetup implements ServiceProvider
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
     * @return WordpressFacilizer
     */
    protected function loadFacilizer() : WordpressFacilizer
    {
        return (new WordpressFacilizer($this));
    }

    /**
     * @return Config
     */
    public function getConfig() : Config
    {
        return $this->config;
    }

    /**
     * @return WordpressFacilizer
     */
    public function getFacilizer(): WordpressFacilizer
    {
        if ($this->facilizer === null) {
            $this->facilizer = $this->loadFacilizer();
        }

        return $this->facilizer;
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
            echo $this->loadMarkupsFile($markupsFilename);
            $flag = true;
        }

        do_action('theme_favicon_setup', $flag);
    }

    /**
     * Loads the data from markups file and prepares it for the theme.
     *
     * @param string $filename
     * @return string
     * @see prepareMarkupsDataForTheme()
     */
    protected function loadMarkupsFile(string $filename) : string
    {
        $data = file_get_contents($filename);

        return $this->prepareMarkupsDataForTheme($data);
    }

    /**
     * Prepares the favicons markups data to be included in the theme.
     *
     * This function only finds the 'href' and 'content' HTML attributes with filename values and prepends them
     * the theme's URI.
     * Otherwise if not added, the web browsers can't figure the right URI to the favicons.
     *
     * @param string $data The data to prepare.
     * @return string
     */
    protected function prepareMarkupsDataForTheme(string $data) : string
    {
        $url = $this->getConfig()->getThemeRootUri();
        $newData = preg_replace(
            '/\s+(href|content)\s*=\s*"(.*\.\w{1,4})"/',
            ' $1="'.$url.'/$2"',
            $data
        );

        return $newData;
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