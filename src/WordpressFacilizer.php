<?php
namespace Madez;

class WordpressFacilizer
{
    use ServiceProviderCustomer;

    /**
     * @param string $name
     * @param string $rel_filename Relative path to filename within the theme.
     * @param array $dependencies Defaults to none (empty array).
     * @param int $refreshOption One of StylesheetRefreshOptions constants. Defaults to StylesheetRefreshOptions::AT_FILE_CHANGE.
     * @param string $media Defaults to 'all'.
     */
    public function loadStylesheet(string $name, string $rel_filename, array $dependencies = [],
                                          int $refreshOption = FileRefreshOptions::AT_FILE_CHANGE, string $media = 'all')
    {
        $verArg = FileRefreshOptions::getVersionByRefreshOption($refreshOption, $rel_filename,
            $this->getServiceProvider()->getConfig());
        $uri = $this->resolveScriptUri($rel_filename);

        wp_enqueue_style($name, $uri, $dependencies, $verArg, $media);
    }

    /**
     * @param string $name
     * @param string $rel_filename
     * @param array $dependencies
     * @param int $refreshOption
     * @param bool $inFooter
     */
    public function loadScript(string $name, string $rel_filename, array $dependencies = [], int $refreshOption = FileRefreshOptions::AT_FILE_CHANGE,
                                      $inFooter = false)
    {
        $verArg = FileRefreshOptions::getVersionByRefreshOption($refreshOption, $rel_filename,
            $this->getServiceProvider()->getConfig());
        $uri = $this->resolveScriptUri($rel_filename);

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
    public function resolveScriptUri(string $rel_filename)
    {
        $uri = $this->getServiceProvider()->getConfig()->getThemeRootUri().'/'.$rel_filename;

        return $uri;
    }
}