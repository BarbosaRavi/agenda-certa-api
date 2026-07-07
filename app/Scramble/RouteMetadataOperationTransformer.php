<?php

namespace App\Scramble;

use Dedoc\Scramble\Contracts\OperationTransformer;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\Type;
use Dedoc\Scramble\Support\Generator\Combined\AllOf;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Support\Str;

class RouteMetadataOperationTransformer implements OperationTransformer
{
    public function handle(Operation $operation, RouteInfo $routeInfo): void
    {
        $this->addPermissionExtension($operation, $routeInfo);
        $this->removeRouteParametersFromRequestBody($operation);
    }

    private function addPermissionExtension(Operation $operation, RouteInfo $routeInfo): void
    {
        $permissions = collect($routeInfo->route->gatherMiddleware())
            ->filter(fn (string $middleware): bool => Str::startsWith($middleware, 'can:'))
            ->map(fn (string $middleware): string => Str::before(Str::after($middleware, 'can:'), ','))
            ->values()
            ->all();

        if ($permissions === []) {
            return;
        }

        $operation->setExtensionProperty(
            'permission',
            count($permissions) === 1 ? $permissions[0] : $permissions,
        );
    }

    private function removeRouteParametersFromRequestBody(Operation $operation): void
    {
        if ($operation->requestBodyObject === null) {
            return;
        }

        $pathParameterNames = collect($operation->parameters)
            ->filter(fn ($parameter): bool => $parameter instanceof Parameter && $parameter->in === 'path')
            ->map(fn ($parameter): string => $parameter->name)
            ->values()
            ->all();

        if ($pathParameterNames === []) {
            return;
        }

        foreach ($operation->requestBodyObject->content as $schema) {
            $this->removeProperties($schema, $pathParameterNames);
        }

        $hasBodyProperties = collect($operation->requestBodyObject->content)
            ->contains(fn ($schema): bool => $this->hasBodyProperties($schema));

        if (! $hasBodyProperties) {
            $operation->requestBodyObject = null;
        }
    }

    /**
     * @param  string[]  $propertyNames
     */
    private function removeProperties(Schema|Reference $schema, array $propertyNames): void
    {
        $type = $schema instanceof Reference ? $schema->resolve()->type : $schema->type;

        $this->removePropertiesFromType($type, $propertyNames);
    }

    /**
     * @param  string[]  $propertyNames
     */
    private function removePropertiesFromType(Type $type, array $propertyNames): void
    {
        if ($type instanceof ObjectType) {
            foreach ($propertyNames as $propertyName) {
                unset($type->properties[$propertyName]);
            }

            $type->setRequired(array_values(array_diff($type->required, $propertyNames)));

            return;
        }

        if ($type instanceof AllOf) {
            foreach ($type->items as $item) {
                $this->removePropertiesFromType($item, $propertyNames);
            }
        }
    }

    private function hasBodyProperties(Schema|Reference $schema): bool
    {
        $type = $schema instanceof Reference ? $schema->resolve()->type : $schema->type;

        return $this->typeHasBodyProperties($type);
    }

    private function typeHasBodyProperties(Type $type): bool
    {
        if ($type instanceof ObjectType) {
            return count($type->properties) > 0;
        }

        if ($type instanceof AllOf) {
            return collect($type->items)->contains(
                fn (Type $item): bool => $this->typeHasBodyProperties($item),
            );
        }

        return true;
    }
}
