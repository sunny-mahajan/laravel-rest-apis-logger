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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.css" type="text/css" rel="stylesheet">
    

    <style>
        .container{max-width: 90%;}

        a.link.disabled {
            pointer-events: none;
        }
        .border-muted{
            box-shadow:0 1px 10px 0 #d7d9dc;
            height:50px;
            border-radius: 6px;
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
        .link{
            cursor: pointer;
        }
        .link:hover{
            text-decoration: none;
        }
        .collapse:not(.show) {
            display: block;
            height: 1.5rem;
            overflow: hidden;
        }
        .collapsing {
            height: 1.5rem;
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
                    <div>
                        <table class="border-muted">
                            <tr>
                                <td><a class="{{!Request::input('m') ? 'btn btn-info' : 'btn link' }} ml-2" href="?r={{Request::input('r')}}">ALL</a></td>
                                <td><a class="{{Request::input('m') == 'GET' ? 'btn btn-success' : 'btn link' }}" href="?m=GET&r={{Request::input('r')}}">GET</a></td>
                                <td><a class="{{Request::input('m') == 'POST' ? 'btn btn-secondary' : 'btn link' }}" href="?m=POST&r={{Request::input('r')}}">POST</a></td>
                                <td><a class="{{Request::input('m') == 'PUT' ? 'btn btn-dark' : 'btn link' }}" href="?m=PUT&r={{Request::input('r')}}">PUT</a></td>
                                <td><a class="{{Request::input('m') == 'PATCH' ? 'btn btn-primary' : 'btn link' }}" href="?m=PATCH&r={{Request::input('r')}}">PATCH</a></td>
                                <td><a class="{{Request::input('m') == 'DELETE' ? 'btn btn-danger' : 'btn link' }} mr-2" href="?m=DELETE&r={{Request::input('r')}}">DELETE</a></td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <table class="border-muted">
                            <tr>
                            <td><a class="{{!Request::input('r') ? 'btn btn-info' : 'btn link' }} ml-2" href="?m={{Request::input('m')}}">ALL</a></td>
                                <td><a class="{{Request::input('r') == 'SUCCESS' ? 'btn btn-success' : 'btn link' }}" id='all' href="?m={{Request::input('m')}}&r=SUCCESS">Success</a></td>
                                <td><a class="{{Request::input('r') == 'FAIL' ? 'btn btn-danger' : 'btn link' }} mr-2" id='get' href="?m={{Request::input('m')}}&r=FAIL">Failed</a></td>
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
                    <div id="pageSize" class="box-size mt-5 mr-5" style="display:none;">
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=50">Show 50</a></div>
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=100">Show 100</a></div>
                        <div><a class="text-dark" href="?m={{Request::input('m')}}&s=500">Show 500</a></div>
                    </div>
                    @if(!is_array($restlogs))
                    <h5><a class="link {{$restlogs->currentPage() < $restlogs->lastPage() ? '' : 'disabled text-dark'}} nounderline" href="?m={{Request::input('m')}}&r={{Request::input('r')}}&p={{$restlogs->currentPage()+1}}&s={{$restlogs->perPage()}}" >&nbsp;>></a> </h5>
                    <div onclick="show();"> <a class="link" >Viewing {{$offset-$restlogs->perPage()+1}}-{{ $offset > $restlogs->total() ?$restlogs->total():$offset }}</a><span> &nbsp;of {{ $restlogs->total() }}</span></div>
                    <h5><a class="link {{$restlogs->currentPage() > 1 ? '' : 'disabled text-dark'}} nounderline" href="?m={{Request::input('m')}}&r={{Request::input('r')}}&p={{$restlogs->currentPage()-1}}&s={{$restlogs->perPage()}}"><<&nbsp;</a> </h5>
                    @endif
                </div>

                <div class="list-group">
                    <?$i = 0?>
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
                        <div id="responseContainer{{$i}}" class="collapse align-items-center">
                            <a class="link float-right text-dark" data-toggle="collapse" data-target="#responseContainer{{$i}}" onclick="changeIcon('icon{{$key}}', 'link{{$i}}')" title="Show more"><span id="link{{$i}}">Show more </span><i class="fa fa-angle-down" style="font-size:20px" id="icon{{$key}}"></i></a>
                            <div class="row w-100 my-2 align-items-center">
                                <? if ($log->header) { ?>
                                <span class="col-md-12 text-break"><b>Request Headers:</b></span>
                                <table class="border m-3">
                                    <tr class="border">
                                        <th class="text-left border pl-2 pr-2">Key</th>
                                        <th class="text-left border pl-2 pr-2">Value</th>
                                    </tr>
                                        <? if(is_string($log->header)) {
                                            $log->header = json_decode($log->header);
                                        }
                                        foreach ($log->header as $key => $value) {
                                            if ($key == 'accept') {
                                                continue;
                                            } ?>                                
                                            <tr width="50%">
                                                <td class="text-left border pl-2 pr-2">{{ json_encode($key) }}</td>
                                                <td class="text-left border pl-2 pr-2">{{ json_encode($value) }}</td>
                                            </tr>
                                        <? } ?>
                                    <? } else { ?>
                                        <div class="d-flex flex-column ml-4">
                                            <span><b>Request Headers:</b></span>
                                            <h6 class="mt-2">N/A</h6>
                                        </div>
                                        <? } ?>
                                </table>
                            </div>
    
                            <hr class="my-2"/>
    
                            <div class="row w-100 my-2 align-items-center">
                                <? if ($log->res_header) { ?>
                                <span class="col-md-12 text-break"><b>Response Headers:</b></span>
                                <table class="border m-3">
                                    <tr class="border">
                                        <th class="text-left border pl-2 pr-2">Key</th>
                                        <th class="text-left border pl-2 pr-2">Value</th>
                                    </tr>
                                    <?
                                        if(is_string($log->res_header)) {
                                            $log->res_header = json_decode($log->res_header);
                                        }
                                        foreach ($log->res_header as $key => $value) {
                                            if ($key == 'accept') {
                                                continue;
                                            } ?>
                                            <tr width="50%">
                                                <td class="text-left border pl-2 pr-2">{{ json_encode($key) }}</td>
                                                <td class="text-left border pl-2 pr-2">{{ json_encode($value) }}</td>
                                            </tr>
                                        <? } ?>
                                    <? } else { ?>
                                        <div class="d-flex flex-column ml-4">
                                            <span><b>Response Headers:</b></span>
                                            <h6 class="mt-2">N/A</h6>
                                        </div>
                                        <? } ?>
                                </table>
                            </div>
    
                            <hr class="my-2"/>
    
                            <div class="row w-100 my-2 align-items-center">
                                @if (strlen($log->payload) > 2)
                                <div class="container-fluid">
                                    <div class="col-md d-flex flex-row justify-content-between mt-2 align-items-center">
                                        <span><b>Request Payload:</b></span>
                                        <span>
                                            <a class="btn btn-secondary btn-sm" onclick="jsonView('payloadJsonButton{{$i}}', 'payload{{$i}}', '{{$log->payload}}', 'payloadCopyText{{$i}}')" id="payloadJsonButton{{$i}}">Raw</a>
                                            <button data-theme="dark" id="payloadCopyText{{$i}}" onclick="copyText('payload-tooltip{{$i}}', '{{$log->payload}}')" style="border:none; background:none; padding-left:10px; visibility:hidden;" title="Copy Text" class="copyText">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x" style="position:relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
                                                            <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
                                                        </g>
                                                    </svg>
                                                    <span id="payload-tooltip{{$i}}" style="visibility: hidden; float: right; position: absolute; right: 0px; word-break: keep-all; transform: translate(100%, 0px);">Copied!</span>						
                                                </span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <span class="col-md-12 text-break ml-3 mt-3 jsonView" id="payload{{$i}}" style="max-height:300px; overflow-y:scroll;"> {{$log->payload}}</span>
                                @else
                                <div class="d-flex flex-column ml-4">
                                    <span><b>Request Payload:</b></span>
                                    <h6 class="mt-2">N/A</h6>
                                </div>
                                @endif
                            </div>

                            <hr class="my-2"/>

                            <div class="row w-100 my-2 align-items-center">
                                @if (strlen($log->res_payload) > 2)
                                <div class="container-fluid">
                                    <div class="col-md d-flex flex-row justify-content-between mt-2 align-items-center">
                                        <span><b>Response Payload:</b></span>
                                        <span>
                                            @if (!($log->res_status > 400))
                                            <a class="btn btn-secondary btn-sm" onclick="jsonView('jsonButton{{$i}}', 'viewMore{{$i}}', '{{$log->res_payload}}', 'copyText{{$i}}')" id="jsonButton{{$i}}">Raw</a>
                                            @endif
                                            <button data-theme="dark" id="copyText{{$i}}" onclick="copyText('custom-tooltip{{$i}}', '{{$log->res_payload}}')" style="border:none; background:none; padding-left:10px; visibility:hidden;" title="Copy Text" class="copyText">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x" style="position:relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
                                                            <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
                                                        </g>
                                                    </svg>
                                                    <span id="custom-tooltip{{$i}}" style="visibility: hidden; float: right; position: absolute; right: 0px; word-break: keep-all; transform: translate(100%, 0px);">Copied!</span>						
                                                </span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <span id ="viewMore{{$i}}" class="col-md-12 text-break ml-3 mt-3 jsonView" style="max-height:300px; overflow-y:scroll;">{{$log->res_payload}}</span>
                                @else
                                <div class="d-flex flex-column ml-4">
                                    <span><b>Response Payload:</b></span>
                                    <h6 class="mt-2">N/A</h6>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <?$i++?>
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
        $(document).ready(function(){
            var container = document.getElementsByClassName('jsonView');
            var containerArr = Array.prototype.slice.call(container, 0);
            containerArr.map(function(item){
                if(item.innerHTML.includes('!doctype')) {
                    $(`#${item.id}`).JSONView(JSON.stringify(item.innerHTML), {collapsed: true, withQuotes: true, withLinks: true});
                } else {
                    $(`#${item.id}`).JSONView(item.innerHTML, {collapsed: false, withQuotes: true, withLinks: true});
                }
            });
        });

        function show() {
            var x = document.getElementById("pageSize");
            if (x.style.display == "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function jsonView(buttonId, textId, data, copyTextId) {
            var item = document.getElementById(buttonId);
            var container = document.getElementById(textId);
            var text = JSON.parse(data);
            if (item.innerHTML == "Raw") {
                item.innerHTML = "Preview";
                container.innerText = JSON.stringify(text);
                document.getElementById(copyTextId).style.visibility = "visible";
            } else {
                item.innerHTML = "Raw";
                $(`#${textId}`).JSONView(text, {collapsed: false, withQuotes: true, withLinks: true});
                document.getElementById(copyTextId).style.visibility = "hidden";
            }
        }

        function copyText(tooltipId, data){
            copyToClipboard(data);
            document.getElementById(tooltipId).style.visibility = 'visible';
            setTimeout( function() {
                document.getElementById(tooltipId).style.visibility = "hidden";
            }, 1000);
        }

        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }

        function changeIcon(id, linkId) {
            if ($(`#${id}`).attr('class') == 'fa fa-angle-up') {
                $(`#${id}`).attr('class', 'fa fa-angle-down');
                $(`#${linkId}`).text('Show more ');
            } else {
                $(`#${id}`).attr('class', 'fa fa-angle-up');
                $(`#${linkId}`).text('Show less ');
            }
        }
     </script>
</body>
</html>

