<?php

declare(strict_types=1);

namespace LaraSsn\LaravelPulseGraphql\Http\Middlewares;

use Closure;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Type\Schema;
use Illuminate\Support\Carbon;
use Rebing\GraphQL\Support\ExecutionMiddleware\AbstractExecutionMiddleware;
use Rebing\GraphQL\Support\OperationParams;

class SetStartedAtGraphGLMiddleware extends AbstractExecutionMiddleware
{
    public function handle(string $schemaName, Schema $schema, OperationParams $params, $rootValue, $contextValue, Closure $next): ExecutionResult
    {
        PassGraphQLEventToPulseMiddleware::$startAt    = Carbon::now();
        PassGraphQLEventToPulseMiddleware::$schemaName = $schemaName;

        return $next($schemaName, $schema, $params, $rootValue, $contextValue);
    }
}
