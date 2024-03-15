@extends('layouts.app')


@section('title')
    Return Product Invoice
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('sales_return.add') }}">Add Sale Return</a>
{{--                    <a class="btn btn-sm btn-warning" target="_blank" href="{{route('sale_return.trash_view')}}">View Trash</a>--}}
                    <hr>
                    <div class="table-responsive">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order No</th>
                            <th>Customer Name</th>
                            <th>Customer Address</th>
                            <th>Customer Phone</th>
                            <th>Branch</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($productReturnOrders as $productReturnOrder)
                            <tr>
                                <td>{{ $productReturnOrder->date }}</td>
                                <td>{{ $productReturnOrder->order_no }}</td>
                                <td>{{ $productReturnOrder->customer->name }}</td>
                                <td>{{ $productReturnOrder->customer->address }}</td>
                                <td>{{ $productReturnOrder->customer->mobile_no }}</td>
                                <td>{{$productReturnOrder->companyBranch->name ?? ''}}</td>
                                <td>{{ $productReturnOrder->quantity }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('return_invoice.details', ['order' => $productReturnOrder->id]) }}"><i class="fa fa-eye"></i></a>
{{--                                    @if(auth()->user()->role != 2)--}}
{{--                                        <a class="btn btn-danger btn-sm btn_delete" data-id="{{$productReturnOrder->id}}"><i class="fa fa-trash"></i></a>--}}
{{--                                    @endif--}}
                                </td>
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
    <script>
        $(function () {
            $('#table').DataTable();
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
