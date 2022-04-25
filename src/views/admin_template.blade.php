<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ ($page_title)?get_setting('appname').': '.strip_tags($page_title):"Admin Area" }}</title>
    <?php 
        $titleofpageComple = ($page_title)?Session::get('appname').': '.strip_tags($page_title):"Admin Area"; 
        // on découpe le titre
        // we cut the title and get the rest
        $morceau = substr($titleofpageComple,19);
        // get the filiale number
        $filiale_number = substr($titleofpageComple,55);
    ?>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name='generator' content='CRUDBooster {{ \charliesolutions\crudbooster\commands\CrudboosterVersionCommand::$version }}'/>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon"
          href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.4.1 -->
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="{{asset("vendor/crudbooster/assets/adminlte/font-awesome/css")}}/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="{{asset("vendor/crudbooster/ionic/css/ionicons.min.css")}}" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css"/>

    <!-- support rtl-->
    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset("vendor/crudbooster/assets/rtl.css")}}" rel="stylesheet" type="text/css"/>
    @endif

    <link rel='stylesheet' href='{{asset("vendor/crudbooster/assets/css/main.css") }}'/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <style>
      .modal-title {
          display: inline-block !important;
      }
    </style>

    <!-- load css -->
    <style type="text/css">
        /* @if($style_css)
            {!! $style_css !!}
        @endif */
    </style>
    @if($load_css)
        @foreach($load_css as $css)
            <link href="{{$css}}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif

    <style type="text/css">
        .dropdown-menu-action {
            left: -130%;
        }

        .btn-group-action .btn-action {
            cursor: default
        }

        #box-header-module {
            box-shadow: 10px 10px 10px #dddddd;
        }

        .sub-module-tab li {
            background: #F9F9F9;
            cursor: pointer;
        }

        .sub-module-tab li.active {
            background: #ffffff;
            box-shadow: 0px -5px 10px #cccccc
        }

        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            border: none;
        }

        .nav-tabs > li > a {
            border: none;
        }

        .breadcrumb {
            margin: 0 0 0 0;
            padding: 0 0 0 0;
        }

        .form-group > label:first-child {
            display: block
        }
        .multiselect {
        width: 200px;
        }

        .selectBox {
        position: relative;
        }

        .selectBox select {
        width: 100%;
        font-weight: bold;
        }

        .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        }

        #checkboxes {
        display: none;
        border: 1px #dadada solid;
        }

        #checkboxes label {
        display: block;
        }

        #checkboxes label:hover {
        background-color: #1e90ff;
        }
        /*  */
        #checkboxes_days {
        display: none;
        border: 1px #dadada solid;
        }

        #checkboxes_days label {
        display: block;
        }

        #checkboxes_days label:hover {
        background-color: #1e90ff;
        }

        #table_dashboard.table-bordered, #table_dashboard.table-bordered thead tr th, #table_dashboard.table-bordered tbody tr td {
            border: 1px solid #bbbbbb !important;
        }
    </style>

    @stack('head')
</head>
<body class="@php echo 'skin-red '; echo config('crudbooster.ADMIN_LAYOUT'); @endphp {{($sidebar_mode)?:''}}">
<div id='app' class="wrapper">

    <!-- Header -->
@include('crudbooster::header')

<!-- Sidebar -->
@include('crudbooster::sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
            <?php
            $module = CRUDBooster::getCurrentModule();
            ?>
            {{-- {{  $morceau }} --}}
            @if($module)
                <h1>
                    <!--Now you can define $page_icon alongside $page_tite for custom forms to follow CRUDBooster theme style -->
                  <!--  <i class='{!! ($page_icon)?:$module->icon !!}'></i> {!! ucwords(($page_title)?:$module->name) !!} &nbsp;&nbsp;-->

                    <!--START BUTTON -->

                    @if(CRUDBooster::getCurrentMethod() == 'getIndex')
                        @if($button_show)
                            <a href="{{ CRUDBooster::mainpath().'?'.http_build_query(Request::all()) }}" id='btn_show_data' class="btn btn-sm btn-primary"
                               title="{{cbLang('action_show_data')}}">
                                <i class="fa fa-table"></i> {{cbLang('action_show_data')}}
                            </a>
                        @endif

                        @if($button_add && CRUDBooster::isCreate())
                            <a href="{{ CRUDBooster::mainpath('add').'?return_url='.urlencode(Request::fullUrl()).'&parent_id='.g('parent_id').'&parent_field='.$parent_field }}"
                               id='btn_add_new_data' class="btn btn-sm btn-success" title="{{cbLang('action_add_data')}}">
                                <i class="fa fa-plus-circle"></i> {{cbLang('action_add_data')}}
                            </a>
                        @endif
                    @endif
                    <!-- Statistique button -->
                    <!-- We use the title to get the number and the type(suivi/isolé) of the materiel to show/hide the button from the user -->
                    {{-- @for ($i = 1; $i <=1; $i++)
                        <input id="prodId" name="prodId" type="hidden" value={{ $i }}>
                        @if($morceau == "statistiques-suivi-materiels-filiale".$i)
                            <?php
                            if($morceau == "es-suivi-materiels-filiale".$i){
                                $id = "rapport_".$i;
                            }
                            ?>
                            @if(CRUDBooster::myPrivilegeId() == 1 || CRUDBooster::myPrivilegeId() == 2)
                                <a type="submit" class="btn btn-sm btn-primary" href='{{ url('report-form/'.$id) }}'>Générer un rapport</a>
                            @endif
                        @endif
                    @endfor --}}


                    @if($button_export && CRUDBooster::getCurrentMethod() == 'getIndex')
                        <a href="javascript:void(0)" id='btn_export_data' data-url-parameter='{{$build_query}}' title='Export Data'
                           class="btn btn-sm btn-primary btn-export-data">
                            <i class="fa fa-upload"></i> {{cbLang("button_export")}}
                        </a>
                    @endif

                    @if($button_import && CRUDBooster::getCurrentMethod() == 'getIndex')
                        <a href="{{ CRUDBooster::mainpath('import-data') }}" id='btn_import_data' data-url-parameter='{{$build_query}}' title='Import Data'
                           class="btn btn-sm btn-primary btn-import-data">
                            <i class="fa fa-download"></i> {{cbLang("button_import")}}
                        </a>
                    @endif
                    {{-- Button to show the Config GPS Modal --}}
                    @if($morceau == "Zone de stockage")
                            <!-- <a type="submit" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gpsConfigModal">Config GPS</a> -->
                    @endif

                <!--ADD ACTIon-->
                    @if(!empty($index_button))

                        @foreach($index_button as $ib)
                            <a href='{{$ib["url"]}}' id='{{str_slug($ib["label"])}}' class='btn {{($ib['color'])?'btn-'.$ib['color']:'btn-primary'}} btn-sm'
                               @if($ib['onClick']) onClick='return {{$ib["onClick"]}}' @endif
                               @if($ib['onMouseOver']) onMouseOver='return {{$ib["onMouseOver"]}}' @endif
                               @if($ib['onMouseOut']) onMouseOut='return {{$ib["onMouseOut"]}}' @endif
                               @if($ib['onKeyDown']) onKeyDown='return {{$ib["onKeyDown"]}}' @endif
                               @if($ib['onLoad']) onLoad='return {{$ib["onLoad"]}}' @endif
                            >
                                <i class='{{$ib["icon"]}}'></i> {{$ib["label"]}}
                            </a>
                    @endforeach
                @endif
                <!-- END BUTTON -->
                </h1>


                <ol class="breadcrumb">
                    <li><a href="{{CRUDBooster::adminPath()}}"><i class="fa fa-dashboard"></i> {{ cbLang('home') }}</a></li>
                    <li class="active">{{ $module->name }}</li>
                </ol>
                <br/>
            @else
                <h1>{{Session::get('appname')}}
                    <small> {{ cbLang('text_dashboard') }} </small>
                </h1>
            @endif
        </section>


        <!-- Main content -->
        <section id='content_section' class="content">

            @if(@$alerts)
                @foreach(@$alerts as $alert)
                    <div class='callout callout-{{$alert["type"]}}'>
                        {!! $alert['message'] !!}
                    </div>
                @endforeach
            @endif


            @if (Session::get('message')!='')
                <div class='alert alert-{{ Session::get("message_type") }}'>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> {{ cbLang("alert_".Session::get("message_type")) }}</h4>
                    {!!Session::get('message')!!}
                </div>
            @endif
            
            <!-- if we are in statistic dashboard-->
            {{--
            @if($morceau == "statistiques-suivi-materiels-filiale".$filiale_number)
                <?php 
                /* 
                    laravel request for statistic values with fixed divs
                */
                    // first column
                    $nb_materiels = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->select(DB::raw('COUNT(id)'))
                        ->count();
                    $nb_materiels_perdus = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('COUNT(charlie_materials_filiale'.$filiale_number.'.id)'))
                        ->where('capteur.status','=','Perdu')
                        ->count();
                    $request_val_materiels = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_types as type','type.id','=','charlie_materials_filiale'.$filiale_number.'.id_mat')
                        ->select(DB::raw('IFNULL(SUM(type.prix),0) as total'))
                        ->first();
                    $val_materiels = $request_val_materiels->total;
                    
                    // second column
                    $nb_materiels_service = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('COUNT(charlie_materials_filiale'.$filiale_number.'.id)'))
                        ->where('capteur.status','=','En service')
                        ->count();
                    $request_val_materiels_service = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_types as type','type.id','=','charlie_materials_filiale'.$filiale_number.'.id_mat')
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('IFNULL(SUM(type.prix),0) as total'))
                        ->where('capteur.status','=','En service')
                        ->first();
                    $val_materiels_service = $request_val_materiels_service->total;
                    $nb_materiels_inactif = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('COUNT(charlie_materials_filiale'.$filiale_number.'.id)'))
                        ->where('capteur.status','=','Inactif')
                        ->count();

                    // third column
                    $nb_materiels_dispo = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('COUNT(charlie_materials_filiale'.$filiale_number.'.id)'))
                        ->where('capteur.status','=','Disponible')
                        ->count();
                    $request_val_materiels_dispo = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_types as type','type.id','=','charlie_materials_filiale'.$filiale_number.'.id_mat')
                        ->join('charlie_capteurs as capteur','capteur.id','=','charlie_materials_filiale'.$filiale_number.'.id_capteur')
                        ->select(DB::raw('IFNULL(SUM(type.prix),0) as total'))
                        ->where('capteur.status','=','Disponible')
                        ->first();
                    $val_materiels_dispo = $request_val_materiels_dispo->total;
                    $nb_materiels_a_controler = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->select(DB::raw('COUNT(id)'))
                        ->where('status','=','A CONTROLER')
                        ->count();

                    // fourth column
                    $nb_materiels_hs = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->select(DB::raw('COUNT(id)'))
                        ->where('status','=','HS')
                        ->count();
                    $request_val_materiels_hs = DB::table('charlie_materials_filiale'.$filiale_number)
                        ->join('charlie_types as type','type.id','=','charlie_materials_filiale'.$filiale_number.'.id_mat')
                        ->select(DB::raw('IFNULL(SUM(type.prix),0) as total'))
                        ->where('charlie_materials_filiale'.$filiale_number.'.status','=','HS')
                        ->first();
                    $val_materiels_hs = $request_val_materiels_hs->total;

                ?>
                <div class="statistic-row row">
                    <div id="area1" class="col-sm-3 connectedSortable">
            
                        <div id="43e00d5803da67a47582e43f182fc467" class="border-box">
                            <div class="small-box bg-teal">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels }}</h3>
                                    <p>Total matériels</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-gear-a"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="82679302ef886950a7ab0c1387511c3b" class="border-box">
                            <div class="small-box bg-green	">
                                <div class="inner inner-box">
                                    <h3>{{ $val_materiels }}</h3>
                                    <p>Valeur matériel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-social-euro	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                    
                            <div class="action pull-right">
                                <a href="javascript:void(0)" data-componentid="82679302ef886950a7ab0c1387511c3b" data-name="Small Box" class="btn-edit-component"><i class="fa fa-pencil"></i></a>
                                &nbsp;
                                <a href="javascript:void(0)" data-componentid="82679302ef886950a7ab0c1387511c3b" class="btn-delete-component"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <div id="cc12b4af41c01274f9ba4bda04c15ae8" class="border-box">
                            <div class="small-box bg-aqua	">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_perdus }}</h3>
                                    <p>Matériel perdu</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-timer	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="area2" class="col-sm-3 connectedSortable">
            
                        <div id="a394c9bbd167f16b0899b5a25c3476b4" class="border-box">
                            <div class="small-box bg-green	">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_service }}</h3>
                                    <p>Total matériels en service</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-android-checkmark-circle	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}?q=EN+SERVICE	" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="67e92d9a31681731cfd3cfb6771e743c" class="border-box">
                            <div class="small-box bg-teal">
                                <div class="inner inner-box">
                                    <h3>{{ $val_materiels_service }}</h3>
                                    <p>Valeur matériel en service</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-social-euro	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="cca655ebc1776e9dad77845c0ef6176b" class="border-box">
                            <div class="small-box bg-yellow	">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_inactif }}</h3>
                                    <p>Matériel inactif</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-timer	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="area3" class="col-sm-3 connectedSortable">
            
                        <div id="74f3378da234725f8754bbbbdfbba79d" class="border-box">
                            <div class="small-box bg-teal	">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_dispo }}</h3>
                                    <p>Matériel disponible</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-android-checkmark-circle	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="9754cb31b0c32681553956140455075e" class="border-box">
                            <div class="small-box bg-teal">
                                <div class="inner inner-box">
                                    <h3>{{ $val_materiels_dispo }}</h3>
                                    <p>Valeur matériel disponible</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-social-euro	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}_is	" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="8fbdb26722e6c395bdfe5b14d488bcb0" class="border-box">
                            <div class="small-box bg-red">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_a_controler }}</h3>
                                    <p>Matériel à contrôler</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-timer	"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="area4" class="col-sm-3 connectedSortable">
            
                        <div id="98e757071dec58e524c1e1d3989cddd6" class="border-box">
                            <div class="small-box bg-yellow	">
                                <div class="inner inner-box">
                                    <h3>{{ $nb_materiels_hs }}</h3>
                                    <p>Matériel HS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-gear-a"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div id="868a3014d6c9309fec35f9f99add1a8d" class="border-box">
                            <div class="small-box bg-teal">
                                <div class="inner inner-box">
                                    <h3>{{ $val_materiels_hs }}</h3>
                                    <p>Valeur matériel HS</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-social-euro"></i>
                                </div>
                                <a href="/admin/filiale{{$filiale_number}}" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            --}}
            

        <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <!-- Modal Config GPS-->
    {{-- Geeting the data from the data base of all storage zones --}}
    <?php
        $storage_zones = DB::table('charlie_zone_stockage')->get();
    ?>
    <div class="modal fade" id="gpsConfigModal" tabindex="-1" role="dialog" aria-labelledby="gpsConfigModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 style="background-color: #be1623;width: 40%;margin-left: 30%;color: white;padding: 10px;">Paramétrage du GPS</h3>
            </div>
            <form class="form-signin" method="post" action="{{url('/rgpd-form')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">

                    <div class="multiselect" style=" width: 100%;overflow-y:auto;">
                        <div class="selectBox" onclick="showCheckboxes()">
                          <select style=" width: 100%;overflow-y:auto;">
                            <option>Choisissez une zone de stockage</option>
                          </select>
                          <div class="overSelect"></div>
                        </div>
                        <div id="checkboxes" style="max-height: 100px;overflow-y: auto;">
                            @foreach ($storage_zones as $key)
                                <span for="one"><input type="checkbox" style="margin-left: 2px;" class="checkedzones" name="zones[]" onclick="getDataGPS();" value="{{ $key->id }}" /> &nbsp;{{ $key->name }}</span><br/>
                            @endforeach
                        </div>
                      </div>
                    <br>

                    <div style="text-align: center;">
                    <span>Désactiver le GPS :</span>&nbsp;&nbsp;
                        <input type="radio" name="is_active" id="radiobutton_active" value="1">
                        <label class="label-form">Oui</label> 
                        &nbsp;
                        <input type="radio" name="is_active" id="radiobutton_notactive" value="0" checked>
                        <label class="label-form">Non</label>
                    </div>
                    <br>

                    <div style="text-align: center;">
                        <span class="label-formtime">Heure de début</span>&nbsp;&nbsp;
                        <input type="time" name="start_hour" id="starttime" onchange="checkTimeDebut()">
                        &nbsp;
                        <span class="label-formtime">Heure de fin</span>&nbsp;&nbsp;
                        <input type="time" name="end_hour" id="endtime" onchange="checkTimeFin()">
                    </div>
                    <div style="text-align: center;">
                        <span class='label label-danger' style="visibility: hidden;margin-bottom: 2px;margin-top: 2px;" id="hidden_msg">L’heure de fin doit être strictement supérieur à l’heure de début</span>
                    </div>
                    <br>
                    <div class="multiselect" style=" width: 100%;overflow-y:auto;">
                        <div class="selectBox" onclick="showCheckboxes_days()">
                            <select>
                            <option>Sélectionnez un(des) jour(s)</option>
                            </select>
                            <div class="overSelect"></div>
                        </div>
                        <div id="checkboxes_days" style="overflow-y:auto;">
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="dimanche" value="Sunday" />&nbsp; dimanche</span> <br>
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="lundi" value="Monday" />&nbsp; lundi</span><br>
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="mardi" value="Tuesday" />&nbsp; mardi</span><br>
                            <span ><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="mercredi" value="Wednesday" />&nbsp; mercredi</span><br>
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="jeudi" value="Thursday" />&nbsp; jeudi</span><br>
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="vendredi" value="Friday" />&nbsp; vendredi</span><br>
                            <span><input type="checkbox" style="margin-left: 2px;" name="repeat_date[]" id="samedi" value="Saturday" />&nbsp; samedi</span>
                        </div>
                    </div>
                    <br>

                    <div style="text-align: center;">
                    <span>Se termine le :</span>&nbsp;
                        <input type="radio" name="end_value" value="0" id="end_value_never" onchange="checkIfSelected(this)" checked>&nbsp;
                        <label class="label-form">Jamais</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="end_value" value="1" id="end_value_with_date" onchange="checkIfSelected(this)">
                        <label class="label-form">Le</label>&nbsp; <input type="date" name="end_date" id="end_date_id" disabled>
                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" id="submitbutton" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
        </div>
    </div>
<script>
    var expanded = false;
    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }
    function showCheckboxes_days() {
        var checkboxes = document.getElementById("checkboxes_days");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }

    function checkIfSelected(el) {
        if(el.value == 1){
            document.getElementById("end_date_id").disabled = false;
            document.getElementById("end_date_id").value = "";
        }else{
            document.getElementById("end_date_id").disabled = true;
        }
    }
    function getDataGPS() {
        var checks = document.getElementsByClassName('checkedzones');
        var array = [];
        for(i = 0; i<checks.length; i++ ){
            if(checks[i].checked){
                array.push(checks[i].value);
            }
        }
        if(array.length == 1){
            let element = array[0];
            $.ajax({
                type:"POST",
                url:'/admin/fill-formgps',
                data:{zoneID:element},
                success:function(response){
                    // decoding data
                    var json = JSON.parse(response);
                    // updating the radio button
                    if(json['is_active'] == 1){
                        document.getElementById('radiobutton_active').checked = true;
                        document.getElementById('radiobutton_notactive').checked = false;
                    }else{
                        document.getElementById('radiobutton_active').checked = false;
                        document.getElementById('radiobutton_notactive').checked = true;
                    }
                    // updating the time
                    document.getElementById("starttime").value = json['start_hour'];
                    document.getElementById("endtime").value = json['end_hour'];
                    // splitting the array of days chosen
                    var arraydays = json['repeat_date'].split(",");
                    console.log(arraydays);
                    for(i = 0; i<arraydays.length; i++ ){
                        if(arraydays[i] == "Sunday"){
                            document.getElementById("dimanche").checked = true;
                        }else if(arraydays[i] == "Monday"){
                            document.getElementById("lundi").checked = true;
                        }else if(arraydays[i] == "Tuesday"){
                            document.getElementById("mardi").checked = true;
                        }else if(arraydays[i] == "Wednesday"){
                            document.getElementById("mercredi").checked = true;
                        }else if(arraydays[i] == "Thursday"){
                            document.getElementById("jeudi").checked = true;
                        }else if(arraydays[i] == "Friday"){
                            document.getElementById("vendredi").checked = true;
                        }else if(arraydays[i] == "Saturday"){
                            document.getElementById("samedi").checked = true;
                        }
                    }

                    if(json['end_value'] == 0){
                        document.getElementById('end_value_never').checked = true;
                        document.getElementById('end_value_with_date').checked = false;
                        document.getElementById("end_date_id").value = "";
                    }else{
                        document.getElementById('end_value_never').checked = false;
                        document.getElementById('end_value_with_date').checked = true;
                        document.getElementById("end_date_id").value = json['end_date'];
                    }
                },
                error: function(codeErreur){
                    string = JSON.stringify(codeErreur);
                }
            });

        } else {
            // delete data from form if more than one or none of the zones are selected
            document.getElementById('radiobutton_active').checked = false;
            document.getElementById('radiobutton_notactive').checked = true;
            document.getElementById("starttime").value = "";
            document.getElementById("endtime").value = "";
            document.getElementById("dimanche").checked = false;
            document.getElementById("lundi").checked = false;
            document.getElementById("mardi").checked = false;
            document.getElementById("mercredi").checked = false;
            document.getElementById("jeudi").checked = false;
            document.getElementById("vendredi").checked = false;
            document.getElementById("samedi").checked = false;
            document.getElementById('end_value_never').checked = true;
            document.getElementById('end_value_with_date').checked = false;
            document.getElementById("end_date_id").value = "";
        }
    }

    function checkTimeFin() {
        var starttime = document.getElementById("starttime").value;
        var endtime = document.getElementById("endtime").value;
        var hidden_msg = document.getElementById("hidden_msg");
        if(starttime !== ""){
            if(endtime < starttime){
                document.getElementById("hidden_msg").style.visibility = "visible";
                document.getElementById("submitbutton").disabled = true;
            }else{
                document.getElementById("hidden_msg").style.visibility = "hidden";
                document.getElementById("submitbutton").disabled = false;
            }
            }
    }
    function checkTimeDebut(){
        var starttime = document.getElementById("starttime").value;
        var endtime = document.getElementById("endtime").value;
        var hidden_msg = document.getElementById("hidden_msg");
        if(endtime !== ""){
            if(starttime > endtime ){
            document.getElementById("hidden_msg").style.visibility = "visible";
            document.getElementById("submitbutton").disabled = true;
            }else{
                document.getElementById("hidden_msg").style.visibility = "hidden";
                document.getElementById("submitbutton").disabled = false;
            }
        }
    }
</script>

    <!-- Footer -->
    @include('crudbooster::footer')

</div><!-- ./wrapper -->


@include('crudbooster::admin_template_plugins')

<!-- load js -->
@if($load_js)
    @foreach($load_js as $js)
        <script src="{{$js}}"></script>
    @endforeach
@endif
<script type="text/javascript">
    var site_url = "{{url('/')}}";
    @if($script_js)
        {!! $script_js !!}
    @endif
</script>

@stack('bottom')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>