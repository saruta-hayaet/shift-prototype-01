<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト') }}
        </h2>
    </x-slot>

    <div class="main">
        <form action="{{route('shift.update')}}" method="POST">
            @csrf
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">変更を登録する</button>
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
                                                <select name="am_project1[{{$row->id}}]" id="">
                                                    <option hidden value="{{$tmpProjectId[$row->id][0][0]}}">{{$tmpShift[$row->id][0][0]}}</option>
                                                    @foreach ($projects as $project)
                                                        @if ($project->id == 48)
                                                            <option value="{{$project->id}}">削除</option>
                                                        @else
                                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <select name="am_project2[{{$row->id}}]" id="">
                                                    <option hidden value="{{$tmpProjectId[$row->id][0][1]}}">{{$tmpShift[$row->id][0][1]}}</option>
                                                    @foreach ($projects as $project)
                                                        @if ($project->id == 48)
                                                            <option value="{{$project->id}}">削除</option>
                                                        @else
                                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                                    @endforeach
                                                </select>
                                                <?php
                                                    $tmpAmNumber;
                                                    foreach ($vehicles as $vehicle) {
                                                        if ($vehicle->id == $row->am_vehicle_id) {
                                                            $tmpAmNumber = $vehicle->number;
                                                        }
                                                    }
                                                ?>
                                                <div class="shift-edit-vehicle">
                                                    <p>車両 : </p>
                                                    <select name="am_vehicle[{{$row->id}}]" id="">
                                                        @foreach ($vehicles as $vehicle)
                                                            <option hidden value="{{$row->am_vehicle_id}}"><?php echo $tmpAmNumber;?></option>
                                                            <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <?php $tmpAmNumber = "";?>
                                            </div>
                                            <div class="row-project">
                                                <p class="text-bold">午後</p>
                                                <select name="pm_project1[{{$row->id}}]" id="">
                                                    <option hidden value="{{$tmpProjectId[$row->id][1][0]}}">{{$tmpShift[$row->id][1][0]}}</option>
                                                    @foreach ($projects as $project)
                                                        @if ($project->id == 48)
                                                            <option value="{{$project->id}}">削除</option>
                                                        @else
                                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                                    @endforeach
                                                </select>
                                                <select name="pm_project2[{{$row->id}}]" id="">
                                                    <option hidden value="{{$tmpProjectId[$row->id][1][1]}}">{{$tmpShift[$row->id][1][1]}}</option>
                                                    @foreach ($projects as $project)
                                                        @if ($project->id == 48)
                                                            <option value="{{$project->id}}">削除</option>
                                                        @else
                                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                                    @endforeach
                                                </select>
                                                <?php
                                                    $tmpPmNumber;
                                                    foreach ($vehicles as $vehicle) {
                                                        if ($vehicle->id == $row->pm_vehicle_id) {
                                                            $tmpPmNumber = $vehicle->number;
                                                        }
                                                    }
                                                ?>
                                                <div class="shift-edit-vehicle">
                                                    <p>車両 : </p>
                                                    <select name="pm_vehicle[{{$row->id}}]" id="">
                                                        @foreach ($vehicles as $vehicle)
                                                            <option hidden value="{{$row->pm_vehicle_id}}"><?php echo $tmpPmNumber;?></option>
                                                            <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <?php $tmpPmNumber = "";?>
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
        </form>
    </div>


</x-app-layout>
