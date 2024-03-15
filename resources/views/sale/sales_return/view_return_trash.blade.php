@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Return Product Invoice Trash List
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="btn btn-danger">Deleted Date</th>
                                <th>Return Date</th>
                                <th>Order No</th>
                                <th>Customer Name</th>
                                <th>Customer Address</th>
                                <th>Customer Phone</th>
                                <th>Branch</th>
{{--                                <th>Quantity</th>--}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($productReturnOrders as $productReturnOrder)
                                <tr>
                                    <td class="btn btn-danger">{{ date('Y-m-d',strtotime($productReturnOrder->deleted_at)) }}</td>
                                    <td>{{ date('Y-m-d',strtotime($productReturnOrder->date)) }}</td>
                                    <td>{{ $productReturnOrder->order_no }}</td>
                                    <td>{{ $productReturnOrder->customer->name }}</td>
                                    <td>{{ $productReturnOrder->customer->address }}</td>
                                    <td>{{ $productReturnOrder->customer->mobile_no }}</td>
                                    <td>
                                        @if ($productReturnOrder->customer->company_branch_id == 1)
                                           Level 1
                                        @else
                                            Level 2
                                        @endif
                                    </td>
{{--                                    <td>{{ $productReturnOrder->quantity }}</td>--}}
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

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            $('#table').DataTable({ordering: false});
        })

        $(function () {
            $('body').on('click', '.btn_delete', function (e) {
                returnId = $(this).data('id');
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete This Return Invoice",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete This Return!'
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('return_invoice.delete') }}",
                            data: { returnId: returnId }
                        }).done(function( response ) {
                            if (response.success) {
                                Swal.fire(
                                    'Delete!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                    //window.location.href = response.redirect_url;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });
                    }
                })
            });
        });

    </script>
@endsection
