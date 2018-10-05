<?php
namespace Madez;

/**
 * Provides methods to retrieve configuration info. This class is used by the ChildThemeSetup during setup time. Therefore if you
 * wish you modify the returned values from Config you need to inherit this class and extend ChildThemeSetup by overriding the
 * {@see ChildThemeSetup::loadConfig()} method to return an instance of the Config class you created.
 *
 * @package Madez
 */
class Config
{
    public function getVersion()
    {
        return wp_get_theme()->get('Version');
    }

    /**
     * Returns the original (not translated) text domain found in style.css.
     *
     * @return string
     */
    public function getTextDomain()
    {
        return wp_get_theme()->get('TextDomain');
    }

    /**
     * @return string
     */
    public function getMainStylesheetRelativeFilename()
    {
        return 'style.css';
    }

    /**
     * @return string
     */
    public function getFaviconMarkupsFilename() : string
    {
        return $this->getFaviconBuildDirname().'/markups.html';
    }

    /**
     * @return string
     */
    public function getFaviconBuildDirname() : string
    {
        return $this->getAssetsDirname().'/favicon/build';
    }

    /**
     * @return string
     */
    public function getAssetsDirname() : string
    {
        return $this->getThemeRootDirname().'/assets';
    }

    /**
     * Returns the URI path to the parent theme root directory.
     *
     * @see get_template_directory_uri()
     */
    public function getParentThemeRootRelativeUri()
    {
        return '../'.basename(get_template_directory_uri());
    }

    /**
     * Returns the path to the theme's root uri.
     *
     * @see get_stylesheet_directory_uri()
     */
    public function getThemeRootUri()
    {
        return get_stylesheet_directory_uri();
    }

    /**
     * Returns the path to the theme's root directory.
     *
     * @see get_stylesheet_directory()
     */
    public function getThemeRootDirname()
    {
        return get_stylesheet_directory();
    }
}