<?php

namespace LaraSsn\LaravelPulseGraphql\Http\Middlewares;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Carbon;
use Rebing\GraphQL\Support\Middleware;

class PassGraphQLEventToPulseMiddleware extends Middleware
{
    public static Carbon $startAt;

    public static ?string $schemaName = NULL;

    public function __construct(public $record) {}

    /**
     * @inheritDoc
     */
    public function handle($root, array $args, $context, ResolveInfo $info, Closure $next)
    {
        if (! isset(self::$startAt)) {
            self::$startAt = Carbon::now();
        }

        return $next($root, $args, $context, $info);
    }

    public function terminate($root, array $args, $context, ResolveInfo $info): void
    {
        ($this->record)(self::$startAt, self::$schemaName, $root, $args, $context, $info);
    }
}
