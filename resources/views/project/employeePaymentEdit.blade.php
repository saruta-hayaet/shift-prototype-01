<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件従業員別賃金') }}
        </h2>
    </x-slot>

    <div class="main">
        <p>案件名 : {{ $project->name }}</p>

        {{-- <div class="button">
            <a href="{{route('project.employeePayment',['id'=>$project->id])}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">新規作成</a>
        </div> --}}

        <form class="employeePaymentCreate" action="{{route('project.employeePaymentUpdate',['id'=>$project->id])}}" method="POST">
            @csrf
            <button style="width:100px;" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-4 sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">登録する</button>
            <div class="employeePaymentList">
                <div class="payment-show-content">
                    @foreach ($payments as $payment)
                    <input hidden name="paymentId" value="{{$payment->id}}">
                    <p>{{$payment->employee->name}}</p>
                    <input hidden name="employee" value="{{$payment->employee->id}}">
                        @if ($payment->type == 0)
                            <input checked type="radio" value="0" id="a" name="type">
                            <label for="a">歩合</label>
                            <input class="" type="radio" value="1" id="b" name="type">
                            <label for="b">時給</label>
                        @else
                            <input type="radio" value="0" id="a" name="">
                            <label for="a">歩合</label>
                            <input checked class="" type="radio" value="1" id="b" name="">
                            <label for="b">時給</label>
                        @endif
                        <input type="text" name="amount" placeholder="{{$payment->amount}}">
                    @endforeach
                </div>
            </div>
        </form>
    </div>



</x-app-layout>
