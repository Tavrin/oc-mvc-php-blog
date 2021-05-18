<?php


namespace Core\controller;

use Core\controller\resolvers\RequestAttributeResolver;
use Core\controller\resolvers\RequestResolver;
use Core\http\Request;
use ReflectionMethod;
use function is_array;

class ArgumentsResolver
{
    private array $argumentResolvers;
    public function __construct()
    {
        $this->argumentResolvers = $this->setResolvers();
    }
    public function getArguments(Request $request, array $controller): array
    {
        $params = [];
        $arguments = $this->createArguments($controller);

        foreach ($arguments as $argument) {
            foreach ($this->argumentResolvers as $resolver) {
                if (!$resolver->checkValue($argument, $request)) {
                    continue;
                }

                $params[] = $resolver->setValue($request, $argument);
                break;
            }
        }

        return $params;
    }

    /**
     * @param array $controller
     * @return array
     * @throws \ReflectionException
     */
    private function createArguments(array $controller): array
    {
        $arguments = [];

        if (is_array($controller)) {
            $reflection = new ReflectionMethod($controller[0], $controller[1]);

            foreach ($reflection->getParameters() as $key => $parameter) {
                $arguments[$key]['name'] = $parameter->getName();
                $arguments[$key]['type'] = $parameter->getType() ? $parameter->getType()->getName() : null ;
                $arguments[$key]['hasDefaultValue'] = $parameter->isDefaultValueAvailable();
                $arguments[$key]['defaultValue'] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null ;
                $arguments[$key]['isNullable'] = $parameter->allowsNull();
            }
        }

        return $arguments;
    }

    /**
     * @return array
     */
    private function setResolvers(): array
    {
        return [
            new RequestAttributeResolver(),
            new RequestResolver()
        ];
    }
}
