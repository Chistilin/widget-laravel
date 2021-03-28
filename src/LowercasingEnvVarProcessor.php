<?php
namespace Widget;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class LowercasingEnvVarProcessor implements EnvVarProcessorInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getEnv($prefix, $name, \Closure $getEnv)
    {
        $env = $getEnv($name);

        return strtolower($env);
    }

    public static function getProvidedTypes()
    {
        return [
            'lowercase' => 'string',
        ];
    }
}
