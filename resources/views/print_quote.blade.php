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
  <div class="container" style="margin-top:80px;width:550px;margin:auto;margin-top:20px;">

    <div class='row'>
        <div class="col">
            <h1 style="font-size:60px; color:black">QUOTE</h1>
            <div class="row">
                <div class="col"><h6 style="color:gray">No. {{$invoice->invoice_code}}</h6></div>
                <div class="col" style="text-align:right"><h6 style="color:grey; ">Date: {{$invoice->invoice_date}}</h6></div>
            </div>

            <div class="row" style="margin-top:20px;">
                <div class="col">
                    <h6 style="font-weight:bold">PREPARED FOR</h6>
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
            
                <p style="font-size:13px;">Dear <strong>{{$invoice->customer->customer_name}}</strong>, it is with great pleasure that we present to you a quotation for the <strong>{{$invoice->purpose}}</strong>.</p>
            </div>
        </div>
    </div>
</div>
  

<div class = "container" style="width:550px; margin-auto;">
    <table class="table table-striped table-dense" style="font-size:15px;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th style="color:red">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($invoice->is_quote == 0)
                @foreach($invoice->payables as $payable)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        {{-- for service --}}
                        @if($payable->payable->service_name)
                        <td>{{$payable->payable->service_name}}</td>
                        <td>{{$payable->payable->service_amount}}</td>
                        <td>{{$payable->quantity}}</td>
                        <td style="color:red">{{$payable->amount}}</td>
                        @else 
                        {{-- for item --}}
                        <td>{{$payable->payable->item_name}}</td>
                        <td>{{$payable->payable->selling_price}}</td>
                        <td>{{$payable->quantity}}</td>
                        <td style="color:red">{{$payable->amount}}</td>
                        @endif
                    </tr>
                @endforeach
            @else        
                @foreach($invoice->quoteables as $quoteable)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        {{-- for service --}}
                        @if($quoteable->quoteable->service_name)
                        <td>{{$quoteable->quoteable->service_name}}</td>
                        <td>{{$quoteable->quoteable->service_amount}}</td>
                        <td>{{$quoteable->quantity}}</td>
                        <td style="color:red">{{$quoteable->amount}}</td>
                        @else 
                        {{-- for item --}}
                        <td>{{$quoteable->quoteable->item_name}}</td>
                        <td>{{$quoteable->quoteable->selling_price}}</td>
                        <td>{{$quoteable->quantity}}</td>
                        <td style="color:red">{{$quoteable->amount}}</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
   
    <h6 style="color:black;text-align:right; margin-top:-10px;">Subtotal: {{$invoice->amount}}</h6>
    <h5 style="color:red; text-align:right;font-weight:bold">Total {{$invoice->total_amount}}</h5>
    @if($invoice->discount >0)
    <p style="font-size:14px; color:grey; text-align:right; margin-top:-10px;">Discount Applied</p>
    @endif

 
</div>
<div class="container" style="text-align:left; margin-left:60px;margin-top:px; width:600px;">
 
    <p style="font-size:12px;">
        Please feel free to contact us with any questions or concerns:
        <ul style="font-size:12px; margin-top:-10px;">
            <li>Phone: 09306550892
             </li>
             <li>Facebook: www.facebook.com/JMBComputersN
                </li>
                <li>Physical Store: P2 Magosilom, Cantilan, Surigao del Sur</li>
        </ul>
        
 
    </p>
    <p style="text-align:center"><em>We're excited to serve you!</em></p>
</div>
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