<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.partials.head')
</head>


<body class="hold-transition skin-red sidebar-mini">

    <div id="app">
        <div id="wrapper">

            @include('admin.partials.topbar')
            @include('admin.partials.sidebar')

            <event-hub></event-hub>
            <router-view></router-view>

        </div>
    </div>

    {!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
    <button type="submit">Logout</button>
    {!! Form::close() !!}

    @include('admin.partials.javascripts')
</body>

</html>