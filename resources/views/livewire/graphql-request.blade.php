<x-pulse::card :cols="$cols" :rows="$rows" :class="$class">
    <x-pulse::card-header name="Graphql queries">
        <x-slot:icon>
            @include('pulse-graphql::icons.graphql')
        </x-slot:icon>
        <x-slot:actions>
            <x-pulse::select
                    wire:model.live="orderBy"
                    label="Sort by"
                    :options="[
                    'slowest' => 'slowest',
                    'count' => 'count',
                    'avg' => 'average',
                ]"
                    @change="loading = true"
            />
        </x-slot:actions>
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand" wire:poll.5s="">
        @if ($data->isEmpty())
            <x-pulse::no-results/>
        @else
            <x-pulse::table>
                <colgroup>
                    <col width="0%"/>
                    <col width="0%"/>
                    <col width="100%"/>
                    <col width="0%"/>
                    <col width="0%"/>
                </colgroup>
                <x-pulse::thead>
                    <tr>
                        <x-pulse::th>Schema</x-pulse::th>
                        <x-pulse::th>Type</x-pulse::th>
                        <x-pulse::th>Operation</x-pulse::th>
                        <x-pulse::th class="text-right">Count</x-pulse::th>
                        <x-pulse::th class="text-right">Slowest</x-pulse::th>
                        <x-pulse::th class="text-right">Average</x-pulse::th>
                    </tr>
                </x-pulse::thead>
                <tbody>
                @foreach ($data as $infos)
                    <tr wire:key="{{ $infos->schemaName.$infos->operationType.$infos->operation }}-spacer" class="h-2 first:h-0"></tr>
                    <tr wire:key="{{ $infos->schemaName.$infos->operationType.$infos->operation }}-row">
                        <x-pulse::td class="text-xs">
                            {{$infos->schemaName}}
                        </x-pulse::td>
                        <x-pulse::td class="text-xs">
                            @switch($infos->operationType)
                                @case('query')
                                    <span class="text-xs font-mono px-1 border rounded font-semibold text-blue-400 dark:text-blue-300 bg-blue-50 dark:bg-blue-900 border-blue-200 dark:border-blue-700">
                                        query
                                    </span>
                                    @break
                                @case('mutation')
                                    <span class="text-xs font-mono px-1 border rounded font-semibold text-purple-400 dark:text-purple-300 bg-purple-50 dark:bg-purple-900 border-purple-200 dark:border-purple-700">
                                        mutation
                                    </span>
                                    @break
                                @default
                                    <span class="text-xs font-mono px-1 border rounded font-semibold text-gray-400 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-500">
                                        {{$infos->operationType}}
                                    </span>
                            @endswitch
                        </x-pulse::td>
                        <x-pulse::td class="overflow-hidden max-w-[1px]">
                            <code class="block text-gray-900 dark:text-gray-100 truncate"
                                  title="{{ $infos->operation }}">
                                {{$infos->operation }}
                            </code>
                        </x-pulse::td>
                        <x-pulse::td numeric class="text-gray-700 dark:text-gray-300 font-bold">
                            {{ number_format($infos->count) }}
                        </x-pulse::td>
                        <x-pulse::td numeric class="text-gray-700 dark:text-gray-300">
                            @if ($infos->slowest === null)
                                <strong>Unknown</strong>
                            @else
                                <strong>{{ number_format($infos->slowest) ?: '<1' }}</strong> ms
                            @endif
                        </x-pulse::td>
                        <x-pulse::td numeric class="text-gray-700 dark:text-gray-300">
                            @if ($infos->average === null)
                                <strong>Unknown</strong>
                            @else
                                <strong>{{ number_format($infos->average) ?: '<1' }}</strong> ms
                            @endif
                        </x-pulse::td>
                    </tr>
                @endforeach
                </tbody>
            </x-pulse::table>
        @endif
    </x-pulse::scroll>
</x-pulse::card>
