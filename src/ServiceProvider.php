<?php
namespace Madez;


interface ServiceProvider
{
    /**
     * @return Config
     */
    public function getConfig() : Config;

    /**
     * @return WordpressFacilizer
     */
    public function getFacilizer() : WordpressFacilizer;
}