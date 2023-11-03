<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件従業員別賃金') }}
        </h2>
    </x-slot>

    <div class="main">
        <p>案件名 : {{ $project->name }}</p>

        <form class="employeePaymentCreate" action="{{route('project.employeePaymentStore',['id'=>$project->id])}}" method="POST">
            @csrf
            <button style="width:100px;" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-4 sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">登録する</button>
            <div class="employeePaymentList">
                @foreach ($employees as $employee)
                    <div class="employeePaymentListItem">
                        <p class="w-100-start">{{$employee->name}}</p>
                        <div class="employeePaymentInput">
                            <input hidden name="employeeid" value="{{$employee->id}}">
                            <input type="radio" value="0" id="a" name="type[{{$employee->id}}]">
                            <label for="a">歩合</label>
                            <input class="" type="radio" value="1" id="b" name="type[{{$employee->id}}]">
                            <label for="b">時給</label>
                        </div>
                        <input class="paymentInput" type="price" name="price[{{$employee->id}}]">
                    </div>
                @endforeach
            </div>
        </form>
    </div>


</x-app-layout>
