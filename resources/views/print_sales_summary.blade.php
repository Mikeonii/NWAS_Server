<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Document</title>
</head>
<body onload="window.print()">
   <div class="row">
   <div class="col"><h2> Item Sales Summary</h2></div>
    <div class="col"><img src="{{ asset('jmb_logo.png') }}" width="60%"></div>
   </div>
  
   <div class="row">
    <div class="col"><p>Date Filter: {{ \Carbon\Carbon::parse($fromDate)->format('F d, Y')}} - {{\Carbon\Carbon::parse($toDate)->format('F d,Y')}}</p>
    <p style="margin-top:-15px;">Total Amount: {{$itemSales->sum('amount')}}</p>
    </div>
    <div class="col">
    <p>Total Entries: {{$itemSales->count()}} </p>
    <p style="margin-top:-15px;">Print Date: {{date('Y-m-d')}} </p>
    </div>
   </div>

  <table class='table table-striped dense'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Batch Name</th>
            <th>Customer Name</th>
            <th>Quantity</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($itemSales as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->item->item_name}}</td>
            <td>{{ $item->item->import_batch ? $item->item->import_batch->batch_description : '' }}</td>
            <td>{{$item->invoice->customer->customer_name}}</td>
            <td>{{$item->quantity}}</td>
            <td>{{$item->amount}}</td>
            <td>{{\Carbon\Carbon::parse($item->created_at)->format('F d,Y')}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>

    <p class="text-center">SYSTEM GENERATED PRINT <br>Powered by: JMBComputers</p>
</body>
</html>
<style>
    body{
        font-size:12px;
    }
       .new-page {
        page-break-before: always;
    }
</style>