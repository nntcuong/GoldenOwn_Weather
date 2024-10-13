<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/weather.css') }}">

</head>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

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
                <h1>Unsubscribe from daily weather forecast</h1>
                <form action="{{ route('unsubscribe') }}" method="POST" class="form-inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2">Unsubscribe</button>
                </form>
            @else
                <h1>Register to receive daily weather forecast</h1>
                <form action="{{ route('subscribe') }}" method="POST" class="form-inline">
                    @csrf
                    <input type="text" name="city" id="city" placeholder="Enter your city" required class="input-field">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2">Subscribe</button>
                </form>
            @endif
        </div>

    
        <div class="button-container mt-8">
            <form action="{{ route('weather.form') }}" method="POST">
                @csrf
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded mt-2 md:mt-0 md:ml-2">
                    Back to Weather Dashboard
                </button>
            </form>
        </div>

        <div class="mt-8">
       
        </div>
    </div>
</x-app-layout>
