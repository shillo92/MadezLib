<?php
namespace Madez;

/**
 * Provides common methods for classes that want to be used as customers for the ServiceProvider.
 *
 * @package Madez
 * @see ServiceProvider
 */
trait ServiceProviderCustomer
{
    protected $serviceProvider;

    public function __construct(ServiceProvider $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * @return ServiceProvider
     */
    public function getServiceProvider() : ServiceProvider
    {
        return $this->serviceProvider;
    }
}