<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receive daily weather forecast information') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mx-auto py-6">
<div class="card p-6">
            @if (Auth::user()->is_confirmed)
                <h4>Unsubscribe from daily weather forecast</h4>
                <form action="{{ route('unsubscribe') }}" method="POST" class="form-inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2">Unsubscribe</button>
                </form>
            @else
                <h4>Register to receive daily weather forecast</h4>
                <form action="{{ route('subscribe') }}" method="POST" class="form-inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2">Subscribe</button>
                </form>
            @endif
        </div>

        <div class="mt-8">
   
        </div>
    </div>
</x-app-layout>
