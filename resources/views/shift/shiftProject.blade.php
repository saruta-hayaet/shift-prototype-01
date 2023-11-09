<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件カウント') }}
        </h2>
    </x-slot>

    <div class="main">
        <div class="button-under">
            <a href="{{route('shift.')}}" class="mb-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">トップに戻る</a>
            <a href="{{route('shift.employeeShowShift')}}" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員閲覧用シフト</a>
            <a href="{{route('shift.projectPriceShift')}}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">上代閲覧用シフト</a>
            <a href="{{route('shift.employeePriceShift')}}" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員給与シフト</a>
        </div>

        <div class="">
            <div class="project-count-table">
                <div class="project-count-project-name">
                    @foreach ($projects as $project)
                        <p class="text-bold">{{$project->name}}</p>
                    @endforeach
                </div>
                <div class="project-count-row">
                    @foreach ($projectsForDate as $date => $projectId)
                        <div class="row-inner">
                            <div class="date text-bold">
                                {{ $date }}
                            </div>
                            <div class="row-inner">
                                @foreach ($projects as $project)
                                    <div class="">
                                        <?php $totalCount = 0; ?>
                                        @if (isset($projectId[$project->id]))
                                            <?php $totalCount = count($projectId[$project->id]); ?>
                                        @endif
                                        <p class="">{{ $totalCount }} 件</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
