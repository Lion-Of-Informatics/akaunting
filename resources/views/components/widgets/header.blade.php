@canany(['update-common-widgets', 'delete-common-widgets'])
<div class="pb-2 my-4 lg:my-0{{ !empty($header_class) ? ' ' . $header_class : '' }}">
    <div class="flex justify-between font-medium mb-2">
        <h2 class="text-black" title="{{ $class->model->name }}">
            {{ $class->model->name }}
        </h2>

        <div class="flex items-center">
            @if ($report = $class->getReportUrl())
                <a href="{{ $report }}" class="text-purple text-sm mr-3">
                    <x-link.hover color="to-purple">
                        {{ trans('widgets.view_report') }}
                    </x-link.hover>
                </a>
            @endif

            <x-dropdown id="dropdown-widget-{{ $class->model->id }}">
                <x-slot name="trigger" class="flex" override="class">
                    <span id="dashboard-widget-more-actions" class="w-8 h-8 flex items-center justify-center px-2 py-2 hover:bg-gray-100 rounded-xl text-purple text-sm font-medium leading-6">
                        <span class="material-icons">more_vert</span>
                    </span>
                </x-slot>

                @can('update-common-widgets')
                <x-button
                    type="button"
                    id="dashboard-edit-widget"
                    class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap"
                    override="class"
                    title="{{ trans('general.edit') }}"
                    @click="onEditWidget('{{ $class->model->id }}')"
                >
                    <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                        {{ trans('general.edit') }}
                    </span>
                </x-button>
                @endcan

                @can('delete-common-widgets')
                <div class="dropdown-divider"></div>

                <x-delete-link :model="$class->model" route="widgets.destroy" />
                @endcan
            </x-dropdown>
        </div>
    </div>

    <span class="h-6 block border-b text-black-400 text-xs truncate">
        {{ $class->getDescription() }}
    </span>
</div>
@endcanany
