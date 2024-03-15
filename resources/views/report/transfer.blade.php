@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('title')
    Transfer Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('report.transfer') }}">
                        <div class="row">
                            @if(auth()->user()->company_branch_id==0)
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Company Branch </label>
                                    <select name="company_branch" class="form-control select2" id="company_branch">
                                        <option value="0">All Company Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" {{ $branch->id == request()->get('company_branch') ? 'selected' : '' }}>{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')??date('Y-m-d')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')??date('Y-m-d')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label>	&nbsp;</label>

                                    <input class="btn btn-primary form-control" type="submit" value="Search">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if($result)
                <section class="card">

                    <div class="card-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table table-responsive" id="prinarea">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Date</th>
                                    @if(auth()->user()->company_branch_id !=0)
                                    <th>Type</th>
                                    @endif
                                    <th>Source Branch</th>
                                    <th>Target Branch</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($result as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->date }}</td>
                                        @if(auth()->user()->company_branch_id !=0)
                                        <td>
                                            @if($item->source_com_branch_id == auth()->user()->company_branch_id)
                                                <span class="badge badge-warning">Transfer Amount</span>
                                            @else
                                                <span class="badge badge-info">Received Amount</span>
                                            @endif

                                        </td>
                                        @endif
                                        <td>
                                            @if($item->source_bank_account_id)
                                                {{$item->sourceBankAccount->bank->name}} (AC : {{$item->sourceBankAccount->account_no ?? ''}})
                                            @else
                                                {{$item->sourchBranch->name ?? 'Admin Cash' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->target_bank_account_id)
                                                {{$item->targetBankAccount->bank->name}} (AC : {{$item->targetBankAccount->account_no ?? ''}})
                                            @else
                                                {{$item->targetBranch->name ?? 'Admin Cash' }}
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $item->amount }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr>
                                    @if(auth()->user()->company_branch_id ==0)
                                    <th class="text-right" colspan="4">Total</th>
                                    @else
                                        <th class="text-right" colspan="5">Total</th>
                                    @endif
                                    <th class="text-right">{{ $result->sum('amount') }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            var accountHeadSubTypeSelected = '{{ request()->get('sub_type') }}';

            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            //Initialize Select2 Elements
            $('.select2').select2();

            $('#account_head_type').change(function () {
                var typeId = $(this).val();

                $('#account_head_sub_type').html('<option value="">All Sub Type</option>');

                if (typeId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_account_head_sub_type') }}",
                        data: { typeId: typeId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (accountHeadSubTypeSelected == item.id)
                                $('#account_head_sub_type').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#account_head_sub_type').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });

            $('#account_head_type').trigger('change');
        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection