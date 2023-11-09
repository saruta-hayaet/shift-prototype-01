<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('案件従業員別賃金') }}
        </h2>
    </x-slot>

    <div class="main">
        <p>案件名 : {{ $project->name }}</p>
        @if (session('alert'))
        <div class="alert alert-warning">
            {{ session('alert') }}
        </div>
    @endif
        <div class="button">
            <a href="{{route('project.employeePayment',['id'=>$project->id])}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">新規作成</a>
        </div>

        <div class="paymentShow">
            @foreach ($payments as $payment)
            <div class="payment-show-content">
                <p>{{$payment->employee->name}}</p>
                @if ($payment->payment_type == 0)
                    <p>歩合</p>
                @else
                    <p>時給</p>
                @endif
                <p>￥{{$payment->amount}}</p>
                <a href="{{route('project.employeePaymentEdit',['id'=>$project->id,'employeeId'=>$payment->employee->id])}}" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">編集</a>
            </div>
            @endforeach
        </div>
    </div>



</x-app-layout>
