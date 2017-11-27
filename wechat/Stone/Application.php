<?php


namespace Wechat\Stone;

use ReflectionClass;
use Closure;

class Application
{
    protected $abstract = [];
    protected $instances = [];

    protected $hasBeenBootstrapped = false;

    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (!$concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->abstract[$abstract] = compact('concrete', 'shared');
    }

    protected function getClosure($abstract, $concrete)
    {
        return function ($container, $parameters = []) use ($abstract, $concrete) {

            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $container->$method($concrete, $parameters);
        };
    }

    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->bulid($bootstrapper)->bootstrap($this);
        }
    }

    public function bulid($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        if ($abstract instanceof Closure) {
            $this->instances[$abstract] =  $abstract($this);
        }
        return $this->instances[$abstract];
    }

    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }
}