@extends('backend.layouts.layouts')
@section('content')
    <div class="box-body">
        <div class="amount">
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $data['today']['amount'] }}</h3>
                            <p><b>今日稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $data['week']['amount'] }}</h3>
                            <p><b>本周稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $data['month']['amount'] }}</h3>
                            <p><b>本月稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $data['season']['amount'] }}</h3>
                            <p><b>本季度稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-fuchsia">
                        <div class="inner">
                            <h3>{{ $data['year']['amount'] }}</h3>
                            <p><b>本年度稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>{{ $data['all']['amount'] }}</h3>
                            <p><b>累计全部稿件量</b></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="department-data">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                    <li><a href="#all" data-toggle="tab">全部</a></li>
                    <li><a href="#this-year" data-toggle="tab">本年</a></li>
                    <li><a href="#this-season" data-toggle="tab">本季度</a></li>
                    <li><a href="#this-month" data-toggle="tab">本月</a></li>
                    <li><a href="#this-week" data-toggle="tab">本周</a></li>
                    <li class="active"><a href="#today" data-toggle="tab">今日</a></li>
                    <li class="pull-left header"><i class="fa fa-inbox"></i> 各处室发件量统计</li>
                </ul>
                <div class="tab-content no-padding">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active departments-published-data" id="today">
                        <table id="departments-published-news-today" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['today']['departments'] as $value)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="chart tab-pane departments-published-data" id="this-week">
                        <table id="departments-published-news-week" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['week']['departments'] as $value)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="chart tab-pane departments-published-data" id="this-month">
                        <table id="departments-published-news-month" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['month']['departments'] as $value)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="chart tab-pane departments-published-data" id="this-season">
                        <table id="departments-published-news-season" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['season']['departments'] as $value)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="chart tab-pane departments-published-data" id="this-year">
                        <table id="departments-published-news-year" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['year']['departments'] as $value)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="chart tab-pane departments-published-data" id="all">
                        <table id="departments-published-news-all" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>处室名称</th>
                                    <th>发件数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($data['all']['departments'] as $value)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            {{ $value['name'] }}
                                        </td>
                                        <td>{{ $value['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection