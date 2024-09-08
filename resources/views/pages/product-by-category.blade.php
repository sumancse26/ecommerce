@extends('layout.app')
@section('content')
    @include('component.MenuBar')
    @include('component.ByCategoryList')
    @include('component.TopBrands')
    @include('component.Footer')
    <script>
        (async () => {
            await Category();
            $(".preloader").delay(90).fadeOut(100).addClass('loaded');
            await ByCategory();
            await TopBrands();
        })()
    </script>
@endsection
