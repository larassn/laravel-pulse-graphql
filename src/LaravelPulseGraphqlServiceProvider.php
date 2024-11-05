<?php

namespace LaraSsn\LaravelPulseGraphql;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaraSsn\LaravelPulseGraphql\Http\Middlewares\SetStartedAtGraphGLMiddleware;
use LaraSsn\LaravelPulseGraphql\Livewire\GraphqlRequest;
use Livewire\LivewireManager;

class LaravelPulseGraphqlServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pulse-graphql');

        $this->callAfterResolving('livewire', function (LivewireManager $livewire, Application $app) {
            $livewire->component('pulse.graphql', GraphqlRequest::class);
        });

        // append middleware to execution_middleware
        config([
            'graphql.execution_middleware' => [SetStartedAtGraphGLMiddleware::class, ...config('graphql.execution_middleware', [])],
        ]);
    }
}