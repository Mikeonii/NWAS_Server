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
  <div class="container" style='margin-top:80px; margin-left:50px;'>

    <div class='row'>
        <div class="col">
            <h1 style="font-size:60px; color:black">INVOICE</h1>
            <div class="row">
                <div class="col"><h5 style="color:gray">No. {{$invoice->invoice_code}}</h5></div>
                <div class="col"><h5 style="color:grey; ">Bill Date: {{$invoice->invoice_date}}</h5></div>
            </div>
        </div>
    </div>
</div>
  

  <div
  style="
    height: 10px;
    width: 100%;
    background-image: linear-gradient(to right, red, blue);
  "
></div>

<div class = "container" style="width:550px; margin-auto;">
    <table class="table table-striped table-dense" style="font-size:15px;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                @if($display_price)
                    <th>Price</th>
                    <th>Quantity</th>
                    <th style="color:red">Total</th>
                @else
                <th>Quantity</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->payables as $payable)
            <tr>
                <td>{{$loop->index+1}}</td>
                {{-- for service --}}
                @if($payable->payable->service_name)
                <td>{{$payable->payable->service_name}}</td>
                <!-- if user wants to display the price -->
                    @if($display_price)
                        <td>{{$payable->payable->service_amount}}</td>
                        <td>{{$payable->quantity}}</td>
                        <td style="color:green">{{$payable->amount}}</td>
                    @else
                        <td>{{$payable->quantity}}</td>
                    @endif
                        
                @else 
                {{-- for service --}}
                <td>{{$payable->payable->item_name}}</td>
                    @if($display_price)
                        <td>{{$payable->payable->selling_price}}</td>
                        <td>{{$payable->quantity}}</td>
                        <td style="color:green">{{$payable->amount}}</td>
                    @else
                        <td>{{$payable->quantity}}</td>
                    @endif
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
   
    @if($display_price)
        <h6 style="color:black;text-align:right; margin-top:-10px;">Subtotal: {{$invoice->amount}}</h6>
    @endif
    <h5 style="color:green; text-align:right;font-weight:bold">Total {{$invoice->total_amount}}</h5>
    @if($invoice->discount >0)
    <p style="font-size:14px; color:grey; text-align:right; margin-top:-10px;">Discount Applied</p>
    @endif
    <div class="row" style="margin-top:20px;">
        <div class="col">
            <h6 style="font-weight:bold">BILLED TO</h6>
            <div
            style="
              height: 3px;
              width: 100%;
              background-image: linear-gradient(to right, red, blue);
            "
          > </div>

        <p>{{$invoice->customer->customer_name}}</p>
        <p style="margin-top:-20px;">Purok {{$invoice->customer->customer_purok}}, Brgy.{{$invoice->customer->customer_barangay}}</p>
        <p style="margin-top:-20px;">{{$invoice->customer->customer_municipality}}, Surigao del Sur</p>
        <p style="margin-top:-20px;">{{$invoice->customer->customer_contact_no}}</p>
        </div>
        <div class="col">
            <h6 style="font-weight:bold">PAYMENT DETAILS</h6>
            <div
            style="
              height: 3px;
              width: 100%;
              background-image: linear-gradient(to right, red, blue);
            "
          > </div>
          <p style="">Pay via Cash or Gcash: 09306550892 - Jan Michael B.</p>
          <p style="margin-top:-20px;">Due Date: {{$invoice->due_date}}</p>
        </div>
   
    </div>
 
</div>
<div class="container" style="text-align:left; margin-top:50px; width:600px;margin:auto">
    <p style="color:grey;font-size:10px;">Terms and Conditions:
        <ol style="color:grey;font-size:10px;">
            <li>Late Payment: Failure to pay after the due date will result in a penalty of Php 15 per day.</li>
            <li>Unclaimed Units: If a computer unit is unclaimed after 3 months, it will become the property of JMBComputers.</li>
            <li>Damages: JMBComputers will not be held liable for any damages to the unit after the notice or bill date if it remains unclaimed.</li>
        </ol>
    
</div>
<p style="text-align: center"><em>Thank you for doing business with us!</em></p>
@if(count($invoice->payables)>2)
<div
class="container"
style="
  height: 20%;
  width: 100%;
  background-image: linear-gradient(to right, red, blue);
"
>
@else
<div
class="container"
style="
  height: 20%;
  width: 100%;
  background-image: linear-gradient(to right, red, blue);
  margin-top:70px;
"
>
@endif
<div class="row" style="padding-right:60px; padding-left:60px; padding-top:20px; padding-bottom:10px;">
    
    <div class="col"><img src="{{ asset('jmb_computers_white.png') }}" 
        width="100%" style="margin-top:30px;">
 
    </div>
    <div class="col" style="color:white;width:700px;">
        <h6>Contact us @:</h6>
        <ul style="font-size:12px;">
            <li>www.jmbcomputers.com</li>
            <li>www.facebook.com/JMBComputersN</li>
            <li>janmichaelbesinga873@gmail.com</li>
            <li>P2,Magosilom,Cantilan,Surigao del Sur</li>
        </ul>
    </div>
</div>

</div>
</body>
</html>