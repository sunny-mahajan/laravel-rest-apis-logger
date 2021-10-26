<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rest Logger') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <style>
        .container{max-width: 90%;}

        a.link.disabled {
            pointer-events: none;
        }
        .border-muted{
            box-shadow:0 1px 10px 0 #d7d9dc;
            height:50px;
        }
        .box-size{
            display: none;
            text-align: right;
            height: 80px;
            position: absolute;
            z-index: 99;
            top: 160px;
            background: rgb(255, 255, 255);
            box-shadow: rgb(204 204 204) 0px 0px 10px 0px;
            padding: 7px 18px;
        }
        .nounderline {
            text-decoration: none !important
        }
    </style>
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
            <div class="container text-break">
                <div class="w-100 d-flex justify-content-between">
                    <h3 class="text-center">Rest Logger</h3>
                    <div class="text-center" onclick="callAjax();">
                        <table class="border border-muted">
                            <tr>
                                <td><a class="{{!Request::input('m') ? 'btn btn-info' : 'btn link' }}" id='all' href="/restlogs">ALL</a></td>
                                <td><a class="{{Request::input('m') =='GET' ? 'btn btn-success' : 'btn link' }}" id='get' href="?m=GET">GET</a></td>
                                <td><a class="{{Request::input('m') =='POST'? 'btn btn-secondary' : 'btn link' }}" id='post' href="?m=POST">POST</a></td>
                                <td><a class="{{Request::input('m') =='PUT'? 'btn btn-dark' : 'btn link' }}" id='put' href="?m=PUT">PUT</a></td>
                                <td><a class="{{Request::input('m') =='PATCH'? 'btn btn-primary' : 'btn link' }}" id='put' href="?m=PATCH">PATCH</a></td>
                                <td><a class="{{Request::input('m') =='DELETE'? 'btn btn-danger' : 'btn link' }}" id='delete' href="?m=DELETE">DELETE</a></td>
                            </tr>
                        </table>
                    </div>
                    <form method="POST" action="{{ route('restlogs.deletelogs') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <input type="submit" {{ count($restlogs) == 0 ? 'disabled' : '' }} class="btn btn-danger delete-logs" value="Clear All Logs"
                                onclick="return confirm('Are you sure you want to clear all the logs?')" />
                        </div>
                    </form>
                </div>

                <div class="d-flex flex-row-reverse mt-5">
                    <div id="demo" class="box-size mt-5 mr-5">
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=50">Show 50</a></div>
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=100">Show 100</a></div>
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=500">Show 500</a></div>
                    </div>
                    <h5><a class="link {{$restlogs->currentPage() < $restlogs->lastPage() ? '' : 'disabled text-dark'}} nounderline" href="?m={{Request::input('m')}}&p={{$restlogs->currentPage()+1}}&s={{$restlogs->perPage()}}" >&nbsp;>></a> </h5>
                    <div onclick="show()"> <span class="text-primary">Viewing {{$offset-$restlogs->perPage()+1}}-{{ $offset > $restlogs->total() ?$restlogs->total():$offset }}</span><span> of {{ $restlogs->total() }}</span></div>
                    <h5><a class="link {{$restlogs->currentPage() > 1 ? '' : 'disabled text-dark'}} nounderline" href="?m={{Request::input('m')}}&p={{$restlogs->currentPage()-1}}&s={{$restlogs->perPage()}}"><<&nbsp;</a> </h5>
                </div>

                <div class="list-group">
                    @forelse ($restlogs as $key => $log)
                    <div class="list-group-item list-group-item-action my-2 border">
                        <div class="row w-100 align-items-center">
                            <span class="col-md-3">
                                @if ($log->res_status > 400)
                                <button class="btn btn-danger font-weight-bold">{{$log->method}}</button>
                                @elseif($log->res_status > 300)
                                <button class="btn btn-info font-weight-bold">{{$log->method}}</button>
                                @elseif($log->res_status = 200)
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

                        <hr class="my-3"/>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-4"><b>URL : </b>{{$log->url}}</span>
                            <span class="col-md-8"><b>Models(Retrieved) :</b> {{$log->models}}</span>
                        </div>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-6"><b>Controller :</b> {{$log->controller}}</span>
                            <span class="col-md-6"><b>Action :</b> {{$log->action}}</span>
                        </div>

                        <hr class="my-2"/>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-12 text-break"><b>Request Payload:</b> {{$log->payload}}</span>
                        </div>

                        <hr class="my-2"/>

                        <div class="row w-100 my-2 align-items-center">
                            <span class="col-md-12 text-break"><b>Response Payload:</b> {{$log->res_payload}}</span>
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
    <script>
        function show() {
            var x = document.getElementById("demo");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
     </script>
</body>
</html>

