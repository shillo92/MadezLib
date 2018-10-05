<?php
namespace Madez;

final class FileRefreshOptions
{
    const NOACTION = 0;
    /**
     * Tells Wordpress to load the file every time a change is made.
     */
    const AT_FILE_CHANGE = 1;
    /**
     * Tells Wordpress to load the file every time the theme's version is changed.
     */
    const AT_THEME_VERSION_CHANGE = 2;

    /**
     * @param int $refreshOption
     * @param string $rel_filename
     * @return bool|false|int|string
     */
    public static function getVersionByRefreshOption($refreshOption, $rel_filename, Config $config)
    {
        $verArg = false;

        switch ($refreshOption) {
            case FileRefreshOptions::AT_FILE_CHANGE:
                $filename = $config->getThemeRootDirname().'/'.$rel_filename;
                $verArg = filemtime($filename);
                break;
            case FileRefreshOptions::AT_THEME_VERSION_CHANGE:
                $verArg = $config->getVersion();
                break;
        }

        return $verArg;
    }
}