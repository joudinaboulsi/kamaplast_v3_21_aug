<div class="row">

    <div class="col-xs-12 col-sm-12">
        <table class="footable">
            <thead>
                <tr>
                    <th data-class="expand" data-sort-initial="true"><span title="table sorted by this column on load">Order ID</span></th>
                    <th data-hide="phone,tablet" data-sort-ignore="true">No. of items</th>
                    <th data-hide="phone,tablet"><strong>Payment Method</strong></th>
                    <th data-hide="phone,tablet"><strong></strong></th>
                    <th data-hide="default"> Price</th>
                    <th data-hide="default" data-type="numeric"> Date</th>
                    <th data-hide="default" data-type="numeric"> Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersList as $ol)
                <tr>
                    <td>#{{$ol->order_id}}</td>
                    <td>{{$ol->quantity}}
                        <small>item(s)</small>
                    </td>
                    <td>{{$ol->payment_method}}</td>
                    <td><br><a href="{{ route('user_order_status_path',$ol->order_id) }}" class="btn btn-dark thin btn-sm">view order</a></td>
                    <td>${{number_Format($ol->total,2,".","'")}}</td>
                    <td data-value="78025368997">{{ date("d M Y - h:ia", strtotime($ol->created_at)) }}</td>
                    <td data-value="{{ $ol->order_status_id }}">
                        <span class="label label_color" style="background-color:{{$ol->payment_status_color}}">{{$ol->payment_status}}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
<!--/row-->