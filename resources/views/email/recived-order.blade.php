Order recived form <b> {{$order['customer_name']}}</b>.
<br>
Customer Phone: <b>{{$order['phone']}}</b>
<br>
Order Date: <b>{{$order['order_date']}}</b>
<br>
<a href="{{route('details.order', $order['id'])}}" style="    text-decoration: none;
    background: black;
    color: white;
    padding: 7px 15px;
    margin-top: 12px;
    display: inline-block;
    font-weight: 600;
    font-size: 13px;
    border-radius: 3px;">Order Details</a>