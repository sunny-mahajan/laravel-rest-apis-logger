<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rest Logger') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body style="font-family: 'Roboto', sans-serif; font-size: 0.9rem;line-height: 1.6">
    <div class="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Rest Logger') }}
                </a>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="w-100 d-flex justify-content-between">
                    <h3 class="text-center">Rest Logger</h3>

                    <form method="POST" action="{{ route('restlogs.deletelogs') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-logs" value="Delete Logs">
                        </div>
                    </form>
                </div>

                <div class="list-group">
                    @forelse ($restlogs as $key => $log)
                    <div class="list-group-item list-group-item-action" style="margin:5px">
                        <div class="row w-100 align-items-center">
                            <span class="col-md-3">
                                @if ($log->response>400)
                                <button class="btn btn-danger font-weight-bold">{{$log->method}}</button>
                                @elseif($log->response>300)
                                <button class="btn btn-info font-weight-bold">{{$log->method}}</button>
                                @elseif($log->response=200)
                                <button class="btn btn-success font-weight-bold">{{$log->method}}</button>
                                @else
                                <button class="btn btn-primary font-weight-bold">{{$log->method}}</button>
                                @endif

                                <large class="col-md-2">{{$log->res_status}}</large>
                            </span>

                            <large class="col-md-3"><b>Duration : </b>{{$log->duration * 1000}}ms</large>
                            <large class="col-md-3"><b>Date : </b>{{$log->created_at}}</large>
                            <large class="col-md-3 mb-1"><b>IP :</b> {{$log->ip}}</large>
                        </div>

                        <hr>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-3"><b>URL : </b>{{$log->url}}</span>
                            <span class="col-md-9"><b>Models(Retrieved) :</b> {{$log->models}}</span>
                        </div>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-3"><b>Controller :</b> {{$log->controller}}</span>
                            <span class="col-md-3"><b>Action :</b> {{$log->action}}</span>
                            <span class="col-md-6"><b>Payload : </b>{{$log->payload}}</span>
                        </div>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-12"><b>Response :</b> {{$log->res_payload}}</span>
                        </div>
                    </div>
                    @empty
                    <div class="row w-100 my-2 align-items-center text-center">
                        <h6 class="col-md-12">Looks like you don't have any logs yet</h6>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>
