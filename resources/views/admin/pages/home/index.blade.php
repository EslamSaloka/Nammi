@extends('admin.layouts.master')
@section('title')
    @lang('Home')
@endsection
@section('PageContent')

<div class="row row-sm">
    @include('admin.pages.home.more')
</div>

@endsection
