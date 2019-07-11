# Indenting persistent blade compiler

[![Build Status](https://travis-ci.org/PrinsFrank/IndentingPersistentBladeCompiler.svg?branch=master)](https://travis-ci.org/PrinsFrank/IndentingPersistentBladeCompiler)

Have you ever looked at the HTML Laravel generates and wondered about the mess? That's what I want to solve with this package. [As this is not going to be fixed in Laravel Framework itself](https://github.com/laravel/framework/pull/28768) I decided to make it into a package, and here it is!

## The problem
An example with all replaced content types:
```
// app.blade.php
@push('scripts')
<stack></stack>
<stack></stack>
@endpush
<html>
    <head>
        @stack('scripts')
    </head>
    <body>
        <sidebar>
            @section('sidebar')
            <sidebarcontent></sidebarcontent>
            @show
        </sidebar>
        <container>
            @yield('content')
        </container>
    </body>
</html>
```
```
// main.blade.php
@extends('example.app')

@section('sidebar')
@parent
<sidebarsectioncontent></sidebarsectioncontent>
@endsection

@section('content')
<level-1>
    @include('example.include')
    @includeif('example.include')
    @includewhen(true, 'example.include')
    @includefirst(['example.include', 'example.include'])
    <level-2>
        <level-3>
            @each('example.include', [1,2], 'index')
        </level-3>
    </level-2>
    @component('example.component')
        @slot('title')
            <title></title>
            <title></title>
        @endslot
        <component></component>
        <component></component>
    @endcomponent
</level-1>
@endsection
```
```
// component.blade.php
<wrapper-component>
    <wrapper-title>
        {{ $title }}
    </wrapper-title>
    {{ $slot }}
</wrapper-component>
```
```
// include.blade.php
<include>
</include>
```
Results in the following HTML. What a mess, right?
```
<html>
    <head>
        <stack></stack>
<stack></stack>
    </head>
    <body>
        <sidebar>
                        <sidebarcontent></sidebarcontent>
            
<sidebarsectioncontent></sidebarsectioncontent>
        </sidebar>
        <container>
            <level-1>
    <include>
</include>    <include>
</include>    <include>
</include>    <include>
</include>    <level-2>
        <level-3>
            <include>
</include><include>
</include>        </level-3>
    </level-2>
    <wrapper-component>
    <wrapper-title>
        <title></title>
            <title></title>
    </wrapper-title>
    <component></component>
        <component></component>
</wrapper-component></level-1>
        </container>
    </body>
</html>
```
This package fixes that and generates the following HTML:
```

<html>
    <head>
        <stack></stack>
        <stack></stack>
    </head>
    <body>
        <sidebar>
            <sidebarcontent></sidebarcontent>
            <sidebarsectioncontent></sidebarsectioncontent>
        </sidebar>
        <container>
            <level-1>
                <include>
                </include>
                <include>
                </include>
                <include>
                </include>
                <include>
                </include>
                <level-2>
                    <level-3>
                        <include>
                        </include>
                        <include>
                        </include>
                    </level-3>
                </level-2>
                <wrapper-component>
                    <wrapper-title>
                        <title></title>
                        <title></title>
                    </wrapper-title>
                    <component></component>
                    <component></component>
                </wrapper-component>
            </level-1>
        </container>
    </body>
</html>
```

## Setting things up

Run ```composer require prinsfrank/indenting-persistent-blade-compiler```

Run ```php artisan view:clear``` to clear the already compiled views. Don't forget to do this when deploying to production as well, existing templates will break!!!