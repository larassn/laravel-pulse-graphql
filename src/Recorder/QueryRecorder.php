<?php

namespace LaraSsn\LaravelPulseGraphql\Recorder;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use LaraSsn\LaravelPulseGraphql\Http\Middlewares\PassGraphQLEventToPulseMiddleware;
use Laravel\Pulse\Concerns\ConfiguresAfterResolving;
use Laravel\Pulse\Pulse;
use Laravel\Pulse\Recorders\Concerns\Ignores;
use Laravel\Pulse\Recorders\Concerns\LivewireRoutes;
use Laravel\Pulse\Recorders\Concerns\Sampling;
use Rebing\GraphQL\GraphQL;

class QueryRecorder
{
    use Ignores,
        LivewireRoutes,
        Sampling,
        ConfiguresAfterResolving;


    /**
     * Create a new recorder instance.
     */
    public function __construct(
        protected Pulse $pulse,
    ) {}

    /**
     * Register the recorder.
     */
    public function register(callable $record, Application $app): void
    {
        $this->afterResolving(
            $app,
            GraphQL::class,
            function (GraphQL $graphQL, Application $app) use (&$record) {
                $graphQL->appendGlobalResolverMiddleware(
                    new PassGraphQLEventToPulseMiddleware($record)
                );
            }
        );
    }

    /**
     * Record the request.
     */
    public function record(Carbon $startedAt, string $schemaName, $root, array $args, $context, ResolveInfo $info): void
    {
        if (! Route::is('graphql*')
            || ! in_array((string) $info->parentType, ['Query', 'Mutation'])
        ) {
            return;
        }

        $schemaName    ??= Arr::first(Route::current()->parameters()) ?? Config::get('graphql.default_schema', 'default');
        $operationType = $info->operation->operation;
        $query         = $info->fieldName;

        $this->pulse->record(
            type     : 'graphql_request',
            key      : json_encode([
                $schemaName, $operationType, $query,
            ], flags: JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            value    : $startedAt->diffInMilliseconds(),
            timestamp: $startedAt,
        )->max()->avg()->count();
    }
}