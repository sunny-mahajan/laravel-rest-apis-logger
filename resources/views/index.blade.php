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

                    <form method="POST" action="{{ route('restlogs.deletelogs') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <input type="submit" {{ count($restlogs) == 0 ? 'disabled' : '' }} class="btn btn-danger delete-logs" value="Clear All Logs"
                                onclick="return confirm('Are you sure you want to clear all the logs?')" />
                        </div>
                    </form>
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
                @if($lastPage > 1)
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if($currentPage > 1)
                        <li class="page-item"><a class="page-link" href="?page=1"><<</a></li>
                        @else
                        <li class="page-item"><button type="button" class="page-link" href="?page=1" disabled><<</a></li>
                        @endif
                        @if($currentPage > 1)
                        <li class="page-item"><a class="page-link" href="?page={{$currentPage-1}}"><</a></li>
                        @else
                        <li class="page-item"><button type="button" class="page-link" href="?page={{$currentPage-1}}" disabled><</a></li>
                        @endif
                        @if($currentPage < 3)
                        @for( $i =1; $i <= 3; $i++)
                        <li class="page-item"><a class="page-link" href="?page={{$i}}">{{ $i }}</a></li>
                        @endfor
                        @endif
                        @if($currentPage >= 3)
                        @for( $i = $currentPage-1; $i <= $currentPage+1 && $i <= $lastPage; $i++)
                        <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                        @endfor
                        @endif
                        @if($currentPage < $lastPage-1)
                        <li class="page-item"><a class="page-link" href="?page={{$currentPage+1}}">></a></li>
                        @else
                        <li class="page-item"><button type="button" class="page-link" href="?page={{$currentPage+1}}" disabled>></a></li>
                        @endif
                        @if($currentPage < $lastPage-1)
                        <li class="page-item"><a class="page-link" href="?page={{$lastPage}}">>></a></li>
                        @else
                        <li class="page-item"><button type="button" class="page-link" href="?page={{$lastPage}}" disabled>>></a></li>
                        @endif
                    </ul>
                    <large class="col-md-3"><b>Total Pages  : </b>{{ $lastPage }}</large>
                    <large class="col-md-3"><b>Current Page  : </b>{{ $currentPage }}</large>
                </nav>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
