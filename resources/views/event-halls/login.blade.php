@extends('layouts.eventhall-demo')
@section('title', 'Sign In | Event Hall Management')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-2xl border border-slate-100">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 mb-4 shadow-sm">
                <i class="fa-solid fa-building-user text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="mt-2 text-sm font-medium text-slate-500">Sign in to your Event Hall account</p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4 rounded-md shadow-sm">
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required class="appearance-none relative block w-full px-4 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:z-10 sm:text-sm font-medium transition-colors bg-slate-50" placeholder="admin@example.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required class="appearance-none relative block w-full px-4 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:z-10 sm:text-sm font-medium transition-colors bg-slate-50" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 block text-sm font-medium text-slate-700">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-bold text-brand-600 hover:text-brand-500">
                        Forgot password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors shadow-lg shadow-slate-900/20">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-arrow-right text-slate-600 group-hover:text-slate-400 transition-colors"></i>
                    </span>
                    Sign In
                </button>
            </div>
            
            <div class="text-center mt-6">
                <p class="text-xs font-semibold text-slate-400">DEMO ACCESS: Use <span class="text-slate-600 bg-slate-100 px-2 py-0.5 rounded">manager@savannahill.com.my</span> / <span class="text-slate-600 bg-slate-100 px-2 py-0.5 rounded">password</span></p>
            </div>
        </form>
    </div>
</div>
@endsection
