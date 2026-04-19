<html>
    <head>
        <title>Order Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .order-details {
                width: 100%;
                border-collapse: collapse;
            }
            .order-details th, .order-details td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .order-details th {
                background-color: #f2f2f2;
                vertical-align:  middle;
                text-align: center;
            }
            .order-details td {
                vertical-align: middle;
                text-align: center
            }
            .user-details {
                width: 100%;
                border-collapse: collapse;
            }
            .user-details th, .user-details td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            .user-details th {
                background-color: #f2f2f2;
            }
            
        </style>
    </head>
    <body>
        <div class="header">
            {{-- LOGO --}}
              <img src="data:image/png;base64,{{ $image }}" width="240px">
        </div>
        <table class="user-details">
            <tr>
                <th colspan="4" style="text-align: center; font-size:20px;">USER DETAILS</th>
            </tr>
            <tr>
                <th style="width:20%;">Control #</th>
                <td style="width:30%;">{{ $control_number }}</td>
                <th style="width:20%;">Date</th>
                <td style="width:30%;">{{ $order->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <th style="width:20%;">Name</th>
                <td style="width:30%;">{{ $order->user->name }}</td>
                <th style="width:20%;">Email</th>
                <td style="width:30%;">{{ $order->user->email }}</td>
            </tr>
            <tr>
                <th style="width:20%;">Address</th>
                <td style="width:30%;">
                    @if($order->shipping_address==null)
                        {{ $order->user->address }}
                    @else
                        {{ $order->shipping_address }}
                    @endif
                </td>
                <th style="width:20%;">Phone</th>
                <td style="width:30%;">
                    @if($order->contact_number==null)
                        {{ $order->user->phone }}
                    @else
                        {{ $order->contact_number }}
                    @endif
                </td>
            </tr>
        </table>
        <br>
        <table class="order-details">
            <tr>
                <th style="width:20%;">Image</th>
                <th style="width:30%;">Product</th>
                <th style="width:15%;">Quantity</th>
                <th style="width:15%;">Price</th>
                <th style="width:20%;">Total</th>
            </tr>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <?php 
                    $imagePath = public_path('storage/uploads/products/' . $item->product->image);
                    $imageData = base64_encode(file_get_contents($imagePath));    
                    ?>
                    <img src="data:image/png;base64,{{ $imageData }}" width="50px">
                </td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>P {{ number_format($item->price, 2) }}</td>
                <td>P {{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Amount:</strong></td>
                <td><strong>P {{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;">
                    <strong>
                        STATUS:
                        @if($order->status == 'pending')
                            <span style="color: orange;">PENDING</span>
                        @elseif($order->status == 'completed')
                            <span style="color: green;">COMPLETED</span>
                        @elseif($order->status == 'cancelled')
                            <span style="color: red;">CANCELLED</span>
                        @else
                            <span style="color: gray;">{{ strtoupper($order->status) }}</span>
                        @endif
                    </strong>
                </td>

            </tr>
        </table>

    </body>
</html>