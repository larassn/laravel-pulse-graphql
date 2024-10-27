<?php

namespace LaraSsn\LaravelPulseGraphql;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
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
    }
}