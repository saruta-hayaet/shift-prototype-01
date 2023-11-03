<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('車両作成') }}
        </h2>
    </x-slot>


    <div class="main">
        <div class="vehicle-create-wrap">
            <form action="{{ route('vehicle.store')}}" method="POST">
                @csrf
                <div class="mb-6">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">車両番号</label>
                  <input type="text" name="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="00" required>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">登録する</button>
            </form>

            <form class="csv" action="{{ route('vehicle.csv') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <button class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">インポート</button>
            </form>
        </div>
    </div>

</x-app-layout>
