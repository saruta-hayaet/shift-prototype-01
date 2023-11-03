<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト作成') }}
        </h2>
    </x-slot>

    <form action="{{ route('shift.csv') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">インポート</button>
    </form>

    <form class="shift-table" action="{{route('shift.store')}}" method="POST">
        @csrf
        <div class="column">
            <div class="">
                <input type="date" name="calendar" max="9999-12-31">
            </div>
            @foreach ($employees as $employee)
            <div class="row">
                <p class="name">{{$employee->name}}</p>
                <input type="hidden" name="employee_id[{{$employee->id}}]" value="{{$employee->id}}">
                <div class="date">
                    <p class="">午前</p>
                    <select name="am_project1[{{$employee->id}}]">
                        <option value="47">案件を選択してください</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                    <select name="am_project2[{{$employee->id}}]">
                        <option value="47">案件を選択してください</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                    <select class="car" name="am_vehicle[{{$employee->id}}]">
                        <option value="">車両を選択してください</option>
                        @foreach ($vehicles as $vehicle)
                        <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="date">
                    <p class="">午後</p>
                    <select name="pm_project1[{{$employee->id}}]">
                        <option value="47">案件を選択してください</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                    <select name="pm_project2[{{$employee->id}}]">
                        <option value="47">案件を選択してください</option>
                        @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                    <select class="car" name="pm_vehicle[{{$employee->id}}]">
                        <option value="">車両を選択してください</option>
                        @foreach ($vehicles as $vehicle)
                        <option value="{{$vehicle->id}}">{{$vehicle->number}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach
            <button type="submit">Save</button>
        </div>
    </form>

</x-app-layout>
