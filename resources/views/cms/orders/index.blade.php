
@extends('cms.layouts.app')

@section('content')

{!! Form::open() !!} {!! Form::close() !!}

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-4">
        <h2>Orders Management</h2>
        <ol class="breadcrumb active">
            <li>
                <strong><a href="">Orders</a></strong>
            </li>
        </ol>
    </div>
</div>

<!-- Loading list of Orders Table -->
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>List of Orders</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Purchased Qty</th>
                                    <th>Payment Status</th>
                                    <th>Ship to</th>
                                    <th>Shipping Status</th>               
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    //script for Data Table
    $(document).ready(function(){

         var order_details_route = '{{ route("cms_order_details", ":order_id") }}';
         var user_route = '{{ route("cms_users_details", ":user_id") }}';

        $('#client_table').DataTable( {
           processing: true,
           serverSide: true,
           ajax: {
               url: '/load-orders-table',
               type: 'POST',
               headers: { 'X-CSRF-Token': $('input[name=_token]').val() }
           },
           columns: [
                   
                        { data: "order_id",
                            render: function (data, type, row)
                             {
                               return '<a href="'+ order_details_route.replace(':order_id', row.order_id) +'">#'+ row.order_id +'</a>';
                             }
                        },
                        { data: "customer",
                             render: function (data, type, row)
                             {
                                if(row.user_id == null)
                                    return row.customer;
                                else
                                    return '<a href="'+ user_route.replace(':user_id', row.user_id) +'">'+ row.customer +'</a>';
                             }
                        },
                        { data: "total",
                            render: function (data, type, row)
                             {
                               return '$'+ row.total + '<br> <small>via '+ row.payment_method +'</small>';
                             }
                        },
                        { data: "created_at",
                             render: function (data, type, row)
                             {  

                                month_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                                var date = moment(row.created_at).format("DD ~MM~ YYYY - h:ma");

                                month_index = parseInt(date[4]+date[5]) - 1;
                                month_seq = date.substring(3,7);

                                date = date.replace(month_seq, month_arr[month_index]);
                                
                                return date;
                             }
                        },
                        { data: "quantity" },
                        { data: "payment_status",
                             render: function (data, type, row)
                             {
                                return '<span class="label label_color b_radius" style="background-color:'+row.payment_status_color+'">'+row.payment_status+ '</span>';
                             }
                        },
                        { data: "shipping_address" },
                        { data: "shipping_status",
                             render: function (data, type, row)
                                 { 
                                    return '<span class="label label_color b_radius" style="background-color:'+row.shipping_status_color+'">'+row.shipping_status+ '</span>';
                                 }
                         }

                    ]
       });

    });
</script>


@endsection