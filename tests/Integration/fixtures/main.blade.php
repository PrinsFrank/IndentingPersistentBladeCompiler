@extends('app')

@section('sidebar')
@parent
<sidebarsectioncontent></sidebarsectioncontent>
@endsection

@section('content')
<level-1>
    @include('include')
    @includeif('include')
    @includewhen(true, 'include')
    @includefirst(['include', 'include'])
    <level-2>
        <level-3>
            @each('include', [1,2], 'index')
        </level-3>
    </level-2>
    @component('component')
        @slot('title')
            <title></title>
            <title></title>
        @endslot
        <component></component>
        <component></component>
    @endcomponent
</level-1>
@endsection