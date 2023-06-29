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
   <div class="col"><h2> Services Sales Summary</h2></div>
    <div class="col"><img src="{{ asset('jmb_logo.png') }}" width="60%"></div>
   </div>
  
   <div class="row">
    <div class="col"><p>Date Filter: {{ \Carbon\Carbon::parse($fromDate)->format('F d, Y')}} - {{\Carbon\Carbon::parse($toDate)->format('F d,Y')}}</p>
    <p style="margin-top:-15px;">Total Amount: {{$serviceSales->sum('amount')}}</p>
    </div>
    <div class="col">
    <p>Total Entries: {{$serviceSales->count()}} </p>
    <p style="margin-top:-15px;">Print Date: {{date('Y-m-d')}} </p>
    </div>
   </div>
  <table class='table table-striped dense'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Customer Name</th>
            <th>Supplier Name</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Date Created</th>
        </tr>
    </thead>
    <tbody>
        @foreach($serviceSales as $service)
        <tr>
            <td>{{$service->id}}</td>
            <td>{{$service->service->service_name}}</td>
            <td>{{$service->invoice->customer->customer_name}}</td>
            <td>{{$service->service->supplier->supplier_name}}</td>
            <td>{{$service->quantity}}</td>
            <td>{{$service->amount}}</td>
            <td>{{\Carbon\Carbon::parse($service->created_at)->format('F d,Y')}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <p class="text-center">Print Date: {{date('Y-m-d')}}</p>

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