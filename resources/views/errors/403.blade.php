@extends('layouts.app')
@section('title', '403')

@section('content')
    <div style="text-align: center  ">
        <h1>403 </h1>
        <a style='text-align: center' href="{{url()->previous()}}">返回上一页</a>
    </div>
@stop