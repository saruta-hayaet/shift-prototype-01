<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="shift-button">
            <div class="shift-button-top">
                <a href="{{route('shift.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">シフト作成</a>
                <a href="{{route('shift.edit')}}" class="text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">シフト編集</a>
            </div>
            <div class="button-under">
                <a href="{{route('shift.employeeShowShift')}}" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員閲覧用シフト</a>
                <a href="{{route('shift.employeePriceShift')}}" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員給与シフト</a>
                <a href="{{route('shift.projectPriceShift')}}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">上代閲覧用シフト</a>
                <a href="{{route('shift.project')}}" class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">案件数用シフト</a>
            </div>
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
                                            @for ($i = 0; $i < 2; $i++)
                                                @if (isset($tmpPrice[$row->id][0][$i]))
                                                    <p>上代 : {{$tmpPrice[$row->id][0][$i]}}</p>
                                                @endif
                                            @endfor
                                            @for ($i = 0; $i < 2; $i++)
                                                @if (isset($tmpPrice[$row->id][0][$i]))
                                                    <p>給与 : {{$tmpEmployeePrice[$row->id][0][$i]}}</p>
                                                @endif
                                            @endfor
                                            @foreach ($vehicles as $vehicle)
                                                @if ($vehicle->id == $row->am_vehicle_id)
                                                    <p>NO.{{$vehicle->number}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="row-project">
                                            <p class="text-bold">午後</p>
                                            @if (isset($tmpShift[$row->id][1]))
                                                <p>{!! implode('<br>', $tmpShift[$row->id][1]) !!}</p>
                                            @endif
                                            @for ($i = 0; $i < 2; $i++)
                                                @if (isset($tmpPrice[$row->id][1][$i]))
                                                    <p>上代 : {{$tmpPrice[$row->id][1][$i]}}</p>
                                                @endif
                                            @endfor
                                            @for ($i = 0; $i < 2; $i++)
                                                @if (isset($tmpPrice[$row->id][1][$i]))
                                                    <p>給与 : {{$tmpEmployeePrice[$row->id][1][$i]}}</p>
                                                @endif
                                            @endfor
                                            @foreach ($vehicles as $vehicle)
                                                @if ($vehicle->id == $row->pm_vehicle_id)
                                                    <p>NO.{{$vehicle->number}}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <?php $count++?>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div class="totalPriceWrap">
                        @foreach ($employees as $employee)
                            <div class="totalPrice">
                                @if (isset($totalPrice[$employee->id]))
                                    <p>上代合計 : ¥{{$totalPrice[$employee->id]}}</p>
                                @else
                                    <p>上代合計 : ¥0</p>
                                @endif
                                @if (isset($totalEmployeePrice[$employee->id]))
                                <p>給与合計 : ¥{{$totalEmployeePrice[$employee->id]}}</p>
                                @else
                                    <p>上代合計 : ¥0</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>


</x-app-layout>
