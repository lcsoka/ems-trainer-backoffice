@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => 'Welcome to the EMS Backoffice!',
        'content'     => 'You can manage the users and their trainings on this site. Just use the sidebar to the left to navigate to the corresponding page.',
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
@endphp

@section('content')

@endsection
