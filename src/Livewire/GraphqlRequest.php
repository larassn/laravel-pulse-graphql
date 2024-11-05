<?php

namespace LaraSsn\LaravelPulseGraphql\Livewire;

use Illuminate\Support\Facades\View;
use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Url;

#[Lazy]
class GraphqlRequest extends Card
{
    /**
     * Ordering.
     *
     * @var 'slowest'|'count'
     */
    #[Url(as: 'graphql')]
    public string $orderBy = 'slowest';

    public function render()
    {
        [$data, $time, $runAt] = $this->remember(
            fn () => $this
                ->aggregate(
                    'graphql_request',
                    ['max', 'count', 'avg'],
                    match ($this->orderBy) {
                        'count' => 'co unt',
                        'avg'   => 'avg',
                        default => 'max',
                    })
                ->map(function ($row) {
                    [$schemaName, $operationType, $operation] = json_decode($row->key, flags: JSON_THROW_ON_ERROR);
                    return (object) [
                        'schemaName'    => $schemaName,
                        'operationType' => $operationType,
                        'operation'     => $operation,
                        'count'         => $row->count,
                        'slowest'       => $row->max,
                        'average'       => $row->avg,
                    ];
                }),
            $this->orderBy,
        );
        return View::make('pulse-graphql::livewire.graphql-request', [
            'time'  => $time,
            'runAt' => $runAt,
            'data'  => $data,
        ]);
    }
}