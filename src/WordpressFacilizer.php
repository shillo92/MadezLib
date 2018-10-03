<?php
namespace Madez;

class WordpressFacilizer
{
    /**
     * @param string $name
     * @param string $rel_filename Relative path to filename within the theme.
     * @param array $dependencies Defaults to none (empty array).
     * @param int $refreshOption One of StylesheetRefreshOptions constants. Defaults to StylesheetRefreshOptions::AT_FILE_CHANGE.
     * @param string $media Defaults to 'all'.
     */
    public static function loadStylesheet(string $name, string $rel_filename, array $dependencies = [],
                                          int $refreshOption = FileRefreshOptions::AT_FILE_CHANGE, string $media = 'all')
    {
        $verArg = FileRefreshOptions::getVersionByRefreshOption($refreshOption, $rel_filename);
        $uri = self::resolveScriptUri($rel_filename);

        wp_enqueue_style($name, $uri, $dependencies, $verArg, $media);
    }

    /**
     * @param string $name
     * @param string $rel_filename
     * @param array $dependencies
     * @param int $refreshOption
     * @param bool $inFooter
     */
    public static function loadScript(string $name, string $rel_filename, array $dependencies = [], int $refreshOption = FileRefreshOptions::AT_FILE_CHANGE,
                                      $inFooter = false)
    {
        $verArg = FileRefreshOptions::getVersionByRefreshOption($refreshOption, $rel_filename);
        $uri = self::resolveScriptUri($rel_filename);

        wp_enqueue_script(
            $name,
            $uri,
            $dependencies,
            $verArg,
            $inFooter
        );
    }

    /**
     * @param string $rel_filename
     * @return string
     */
    public static function resolveScriptUri(string $rel_filename)
    {
        $uri = Config::getThemeRootUri().'/'.$rel_filename;

        return $uri;
    }
}