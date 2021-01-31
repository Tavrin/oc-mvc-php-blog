<?php


namespace App\core\controller;

use App\core\controller\resolvers\RequestAttributeResolver;
use App\core\controller\resolvers\RequestResolver;
use App\Core\Http\Request;


class ArgumentsResolver
{
    private $argumentResolvers;
    public function __construct()
    {
        $this->argumentResolvers = $this->setResolvers();
    }
    public function getArguments(Request $request, array $controller)
    {
        $params = [];
        $arguments = $this->createArguments($controller);

        foreach ($arguments as $argument) {
            foreach ($this->argumentResolvers as $resolver) {
                if (!$resolver->checkValue($request, $argument)) {
                    continue;
                }

                $params[] = $resolver->setValue($request, $argument);
                break;

            }
        }

        return $params;
    }

    private function createArguments(array $controller)
    {
        $arguments = [];

        if (\is_array($controller)) {
            $reflection = new \ReflectionMethod($controller[0], $controller[1]);

            foreach ($reflection->getParameters() as $key=>$parameter)
            {
                $arguments[$key]['name'] = $parameter->getName();
                $arguments[$key]['type'] = $parameter->getType() ? $parameter->getType()->getName(): null;
                $arguments[$key]['hasDefaultValue'] = $parameter->isDefaultValueAvailable();
                $arguments[$key]['defaultValue'] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue(): null;
                $arguments[$key]['isNullable'] = $parameter->allowsNull();
            }

        }

        return $arguments;
    }

    private function setResolvers()
    {
        return [
            new RequestAttributeResolver(),
            new RequestResolver()
        ];
    }
}