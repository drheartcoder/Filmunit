@extends('front.layout.master')    

@section('main_content')

<?php echo file_get_contents('http://192.168.1.7/filmunit/wptheme/'); ?>

@endsection