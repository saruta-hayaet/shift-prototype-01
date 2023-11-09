<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <a href="{{route('csv-issue.')}}" style="display: block;width:fit-content"
            class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">検索に戻る</a>

        @if ($shifts->isEmpty())
        <p>条件に一致するシフトがありません</p>
        @else
        <a href="{{route('csv-employee.export',['employeeId'=>$employeeId,'month'=>$month])}}"
            style="display: block;width:fit-content"
            class="mb-10 text-white bg-green-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">csvファイルにエクスポート</a>
        <div class="">
            <p>{{$getEmployee->name}}</p>
            <div class="csv-head">
                <p class="csv-txt-width csv-head-txt b-r">日付</p>
                <p class="am csv-txt-width csv-head-txt b-r">午前</p>
                <p class="pm csv-txt-width csv-head-txt b-r">午後</p>
                <p class="pm csv-txt-width csv-head-txt">給与</p>
            </div>
            <div class="csv-tbody-wrap">
                @foreach ($dates as $date)
                <?php
                    $price = 0;
                    $amflag = false;
                    $pmflag = false;
                ?>
                <div class="csv-tbody">
                    <p class="csv-txt-width b-r">{{$date['display']}}</p>
                    <div class="csv-txt-width b-r">
                        @foreach ($shifts as $shift)
                            @if ($shift->date == $date['compare'])
                                @foreach ($shift->projects as $project)
                                    @if ($shift->employee->id == $getEmployee->id && $project->pivot->time_of_day == 0)
                                        <p class="">
                                            {{$project->name}}
                                        </p>
                                        @foreach ($payments as $payment)
                                            @if($payment->project_id == $project->id)
                                                <?php
                                                 $price+=$payment->amount;
                                                 $amflag = true;
                                                 ?>
                                            @endif
                                        @endforeach
                                        @if(!$amflag)
                                            <?php $price+=$project->driver_price;?>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                    <div class="csv-txt-width b-r">
                        @foreach ($shifts as $shift)
                            @if ($shift->date == $date['compare'])
                                @foreach ($shift->projects as $project)
                                    @if ($shift->employee->id == $getEmployee->id && $project->pivot->time_of_day == 1)
                                        <p class="">
                                            {{$project->name}}
                                        </p>
                                        @foreach ($payments as $payment)
                                            @if($payment->project_id == $project->id)
                                                <?php
                                                 $price+=$payment->amount;
                                                 $pmflag = true;
                                                 ?>
                                            @endif
                                        @endforeach
                                        @if(!$pmflag)
                                            <?php $price+=$project->driver_price;?>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                    <div class="csv-txt-width b-l">
                        <?php echo $price;?>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</x-app-layout>
