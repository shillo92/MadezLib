<?php
namespace Madez;

final class Config
{
    public static function getVersion()
    {
        return wp_get_theme()->get('Version');
    }

    /**
     * Returns the original (not translated) text domain found in style.css.
     *
     * @return string
     */
    public static function getTextDomain()
    {
        return wp_get_theme()->get('TextDomain');
    }

    /**
     * @return string
     */
    public static function getMainStylesheetRelativeFilename()
    {
        return 'style.css';
    }

    /**
     * Returns the URI path to the parent theme root directory.
     *
     * @see get_template_directory_uri()
     */
    public static function getParentThemeRootRelativeUri()
    {
        return '../'.basename(get_template_directory_uri());
    }

    /**
     * Returns the path to the theme's root uri.
     *
     * @see get_stylesheet_directory_uri()
     */
    public static function getThemeRootUri()
    {
        return get_stylesheet_directory_uri();
    }

    /**
     * Returns the path to the theme's root directory.
     *
     * @see get_stylesheet_directory()
     */
    public static function getThemeRootDirname()
    {
        return get_stylesheet_directory();
    }
}