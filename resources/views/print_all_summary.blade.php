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
   
  <h2> <img src="{{ asset('jmb_logo.png') }}" width="30%">Import Batch Items</h2>
  <p>Import Batch: <strong>{{$import_batch->batch_description}}</strong>
   | Date Ordered: <strong>{{$import_batch->date_ordered}}</strong>
   | Date Arrived: <strong>{{$import_batch->date_arrived}}</strong>
  </p>
  <table class='table table-striped'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Item/Service Name</th>
            <th>Customer Name</th>
            <th>Batch Import</th>
            <th>Quantity</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->import_batch->batch_description}}</td>
            <td>{{$item->item_name}}</td>
            <td>{{$item->supplier->supplier_name}}</td>
            <td>{{$item->unit}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <p class="text-center">Print Date: {{date('Y-m-d')}}</p>

</body>
</html>