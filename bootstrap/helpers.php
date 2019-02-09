<?php

function route_class()
{
    return str_replace('.', '-',  Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));

    return str_limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    $model_name = model_plural_name($model);
    $prefix = $prefix ? "/$prefix/" : '/';

    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    return '<a href="'. $url .'" target="_blank">'. $title .'</a>';
}

function model_plural_name($model)
{
    $full_class_name = get_class($model);
    $class_name = class_basename($full_class_name);

    $snake_case_name = snake_case($class_name);

    return str_plural($snake_case_name);
}

function get_memory() {
    $size = memory_get_usage();
    $unit=array('B','KB','MB','GB','TB','PB');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}