<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員閲覧用シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="button-under">
            <a href="{{route('shift.')}}" class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">トップに戻る</a>
            <a href="{{route('shift.projectPriceShift')}}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">上代閲覧用シフト</a>
            <a href="{{route('shift.employeePriceShift')}}" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員給与シフト</a>
            <a href="{{route('shift.project')}}" class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">案件数用シフト</a>
        </div>

        <?php $count = 0;?>
        <div class="wrap">
            <div class="shift">
                <div class="shift-row">
                    @foreach ($shifts as $date => $rows)
                        <div class="shift-row-inner">
                            <div class="date">
                                <p class="text-bold">{{ $date }}</p>
                            </div>
                            <div class="shift-project">
                                @foreach ($rows as $row)
                                    <div class="project-flex">
                                        @if ($count < 31)
                                            <div class="employee-name">
                                                <p class="text-bold">{{$employeeList[$count]}}</p>
                                            </div>
                                        @endif
                                        <div class="row-project">
                                            <p class="text-bold">午前</p>
                                            @if (isset($tmpShift[$row->id][0]))
                                                <p>{!! implode('<br>', $tmpShift[$row->id][0]) !!}</p>
                                            @endif
                                            @foreach ($vehicles as $vehicle)
                                                @if ($vehicle->id == $row->am_vehicle_id)
                                                    <p>No.{{$vehicle->number}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="row-project">
                                            <p class="text-bold">午後</p>
                                            @if (isset($tmpShift[$row->id][1]))
                                                <p>{!! implode('<br>', $tmpShift[$row->id][1]) !!}</p>
                                            @endif
                                            @foreach ($vehicles as $vehicle)
                                                @if ($vehicle->id == $row->pm_vehicle_id)
                                                    <p>No. {{$vehicle->number}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <?php $count++?>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
