@extends('front.layout.master')    

@section('main_content')

<?php 
echo file_get_contents(url('/').'/wptheme/'.$slug);
?>

@endsection