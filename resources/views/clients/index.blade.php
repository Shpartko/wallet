@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Client report
            </div>

            <div class="panel-body">
                <!-- Display Validation Errors -->
                @include('common.errors')

                <!-- Filter -->
                <form id="filter_form" action="{{ url('client') }}" method="POST" class="form-horizontal">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="client_id" class="col-sm-1 control-label">Client</label>

                        <div class="col-sm-3">
                            <select id="client_id" name="client_id" class="form-control">
                                <option></option>
                            @foreach ($clients as $option)
                                <option value="{{ $option->client_id }}" {{ $option->client_id == $client_id ? 'selected' : '' }}>
                                    {{ $option->name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        
                        <label for="date_from" class="col-sm-1 control-label">Date from</label>

                        <div class="col-sm-2">
                            <input type="date" id="date_from" name="date_from" class="form-control" value="{{$date_from }}">
                        </div>
                        
                        <label for="date_to" class="col-sm-1 control-label">Date to</label>

                        <div class="col-sm-2">
                            <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $date_to }}">
                        </div>
                        
                        <div class="col-sm-1">
                            <button id="search_btn" type="button" class="btn btn-default">
                                <i class="fa fa-btn fa-search"></i>Search
                            </button>
                        </div>
                        
                    @if (count($wallet_hist) > 0)
                        <div class="col-sm-1 form-group">
                            <button id="export_btn" type="button" class="btn btn-default">
                                <i class="fa fa-btn fa-table"></i>Export
                            </button>
                        </div>
                    @endif
                    </div>
                </form>
            
            @if (count($wallet_hist) == 0 and !is_null($client_id))
            <h4 class="col-sm-offset-3 col-sm-6 text-center text-danger">Data not found</h4>
            @endif
            
            {{-- Overflow table --}}
            @if (count($wallet_hist) > 0)            
            <div class="row">
                <table id="table_report" class="table-bordered col-sm-offset-1 col-sm-10" caption="{{ $client->name }}">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="6">
                                {{ $client->name }}, {{ $client->country }}, {{ $client->city }}, currency {{ $currency->currency }}@if ($date_to != '' || $date_from !=''), report date: @endif {{ $date_from }} @if ($date_to != '' || $date_from !='') - @endif {{ $date_to }}
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center" title="Operation amount in client currency">Amount</th>
                            <th class="text-center">USD</th>
                            <th class="text-center">Operation</th>
                            <th class="text-center">Partner name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallet_hist as $row)
                        <tr>
                            <td>{{ date('d.m.Y H:i', strtotime($row->hist_date)) }}</td>
                            <td>@if ($row->type =='OUT')-@endif{{ $row->amount / $currency->fractional }}</td>
                            <td>@if ($row->type =='OUT')-@endif{{ $row->operation->usd_amount / 100 }}</td>
                            <td>{{ $row->operation->operation }}</td>
                            <td title="{{ $row->wallet_partner_id }}">{{ $row->wallet_partner_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th>{{ $sum_amount / $currency->fractional }}</th>
                            <th>{{ $sum_usd / 100 }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#date_from').datepicker({format: 'dd.mm.yyyy', weekStart: 1, todayHighlight: true});
        $('#date_to').datepicker({format: 'dd.mm.yyyy', weekStart: 1, todayHighlight: true});

        $('#search_btn').click(function(){
            var $form = $('#filter_form');
            var action = $form.attr('action');
            var client_id = $('#client_id option:selected').val();
            $form.attr('action', action + '/' + client_id);
            $form.submit();
        });

        // This must be a hyperlink
        $("#export_btn").on('click', function () {
            $('#table_report').tableToCSV();
        });
    });
    
    //http://www.jqueryscript.net/table/jQuery-Plugin-To-Convert-HTML-Table-To-CSV-tabletoCSV.html
    jQuery.fn.tableToCSV = function() {    
        var clean_text = function(text){
            text = text.replace(/"/g, '""');
            return '"'+text.trim()+'"';
        };

        $(this).each(function(){
                var table = $(this);
                var caption = $(this).attr('caption');
                var rows = [];
                var delimiter = ";";

                $(this).find('tr').each(function(){
                    var data = [];
                    $(this).find('th, td').each(function(){
                        var text = clean_text($(this).text());
                        data.push(text);
                    });
                    data = data.join(delimiter);
                    rows.push(data);
                });
                rows = rows.join("\n");

                var csv = rows;
                var uri = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
                var download_link = document.createElement('a');
                download_link.href = uri;
                var ts = new Date().getTime();
                if(caption==""){
                        download_link.download = ts+".csv";
                } else {
                        download_link.download = caption+".csv";
                }
                document.body.appendChild(download_link);
                download_link.click();
                document.body.removeChild(download_link);
        });
    };
</script>
@endsection
