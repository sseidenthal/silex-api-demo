<?php

namespace App\Providers;

use Pimple\Container;
use Symfony\Component\Yaml\Parser;
use Pimple\ServiceProviderInterface;

class YamlParametersProvider implements ServiceProviderInterface
{
    /**
     * @var $filename string
     */
    private $filename;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * YamlParametersProvider constructor.
     *
     * @param $filename
     */
    function __construct($filename)
    {
        $this->filename = $filename;
        $this->parser = new Parser();
    }

    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $config = $this->getConfig();
        $this->merge($app, $config);
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        if (!$this->filename) {
            throw new \RuntimeException('A valid configuration file must be passed before reading the config.');
        }

        if (!file_exists($this->filename)) {
            throw new \InvalidArgumentException(
                sprintf("The config file '%s' does not exist.", $this->filename));
        }

        return $this->load();
    }

    /**
     * @return array
     */
    private function load()
    {
        if (!class_exists('Symfony\\Component\\Yaml\\Yaml')) {
            throw new \RuntimeException('Unable to read yaml as the Symfony Yaml Component is not installed.');
        }

        $config = $this->parser->parse(file_get_contents($this->filename));

        return $config ?: array();
    }

    /**
     * @param Container $app
     * @param array $config
     */
    private function merge(Container $app, array $config)
    {
        foreach ($config as $name => $value) {
            $app[$name] = $value;
        }
    }
}
