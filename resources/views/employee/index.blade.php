<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('従業員') }}
        </h2>
    </x-slot>

    <div class="main">
        <a href="{{route('employee.create')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">従業員追加</a>

        <div class="employee-table">
            <div>
              <div class="employee-th">
                <p class="w-100">従業員番号</p>
                <p class="w-100">名前</p>
              </div>
            </div>
            <div class="employee-tbody">
                @foreach ( $employees as $employee )
                  <div class="employee-row">
                    <p class="w-100">{{ $employee->id}}</p>
                    <p class="w-100">{{ $employee->name}}</p>
                    <div class="change w-100">
                        <a href="{{ route('employee.edit',['id'=>$employee->id]) }}">編集</a>
                    </div>
                    <div class="delete w-100">
                        <a href="{{ route('employee.delete',['id'=>$employee->id])}}">削除</a>
                    </div>
                  </div>
                @endforeach
            </div>
          </div>
    </div>

</x-app-layout>
