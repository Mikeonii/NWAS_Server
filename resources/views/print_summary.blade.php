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
            <div class="col-7">
                <h4 style="font-size:30px; color:black; font-weight:bold">WORK SUMMARY</h4>
                <div class="row">
                    {{-- <div class="col"><h6 style="color:gray">No. {{$ode}}</h6></div>
                    <div class="col" style="text-align:right"><h6 style="color:grey; ">Date: {{$ate}}</h6></div> --}}
                </div>
            </div>
            <div class="col-4"><img src="{{ asset('jmb_logo.png') }}" 
                width="140%" style="margin-top:-10px;">
            </div>
        </div>
        
    </div>



    <div
    style="
      height: 3px;
      width: 100%;
      background-image: linear-gradient(to right, red, blue);
    "
    >
    </div>
    <div class="row" style="margin-top:px; padding-right:30px; padding-left:30px;">
        <div class="col">
            <div class="container" style="margin-top:10px;">
                <h6 style="font-weight:bold">PREPARED FOR</h6>
            <p>{{$customer->customer_name}}</p>
            <p style="margin-top:-20px;">Purok {{$customer->customer_purok}}, Brgy.{{$customer->customer_barangay}}</p>
            <p style="margin-top:-20px;">{{$customer->customer_municipality}}, Surigao del Sur</p>
            <p style="margin-top:-20px;">{{$customer->customer_contact_no}}</p>

            </div>
        </div>
        <div class="col" style='margin-top:10px;'>
            <h6 style="font-weight:bold">UNIT INFORMATION</h6>
            <p style="margin-top;">Type: {{$prob->unit->unit_type}}</p>
            <p style="margin-top:-20px;">Brand: {{$prob->unit->unit_brand}}</p>
            <p style="margin-top:-20px;">Model: {{$prob->unit->unit_model}}</p>
            <p style="margin-top:-20px;">S/N: {{$prob->unit->serial_no}}</p>       
        </div>
    </div>
    <hr>
    <div style ="padding-right:30px;
    padding-left:30px;">
    <div class="row">
        <div class="col">
            <p>Initial Problem: <span style="color:orange; font-weight:bold">{{$prob->problem_description}}</span></p>

        </div>
        <div class="col">Date Inserted: <span style="color;font-weight:bold">{{$date_inserted}}</span></div>
    </div>
        <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>No.</th>
                <th>Action</th>
                <th>Results</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $his)
                <tr><td>{{$loop->index+1}}</td>
                    <td>{{$his->get('action')}}</td>
                    <td>{{$his->get('results')}}</td>
                    <td>{{$his->get('remarks')}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row" style="font-size:13px;">
        <div class="col"><p>Technician: <strong>{{$prob->technician}}</strong></p></div>
        <div class="col"><p>Repair Initialized: <strong>{{$prob->repair_initialized}}</strong></p></div>
        @if($prob->status =='Fixed')
        <div class="col sm-4" style="color:Green">Status: <strong>{{$prob->status}}</strong></div>
        @else
        <div class="col sm-4" style="color:red">Status: <strong>{{$prob->status}}</strong></div>
        @endif
    </div>
    <hr>
    <h5>Payments and Warranty</h5>
    <table class="table table-striped table-dense" style="font-size:15px;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th style="color:red">Total</th>
                <th>Warranty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->payables as $payable)
            <tr>
                <td>{{$loop->index+1}}</td>
                {{-- for service --}}
                @if($payable->payable->service_name)
                <td>{{$payable->payable->service_name}}</td>
                <td>{{$payable->payable->service_amount}}</td>
                <td>{{$payable->quantity}}</td>
                <td style="color:green">{{$payable->amount}}</td>
                <td>{{$payable->payable->warranty->warranty_count}} {{$payable->payable->warranty->warranty_duration}}</td>
                @else 
                {{-- for item  --}}
                <td>{{$payable->payable->item_name}}</td>
                <td>{{$payable->payable->selling_price}}</td>
                <td>{{$payable->quantity}}</td>
                <td style="color:green">{{$payable->amount}}</td>
                <td>{{$payable->payable->warranty->warranty_count}} {{$payable->payable->warranty->warranty_duration}}</td>
                @endif
               
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row" style="font-size:13px;">
        <div class="col"><p>Subtotal: {{$invoice->amount}}</p></div>
        <div class="col"><p>Discount: {{$invoice->discount}}</p></div>
        <div class="col"><p>Total: {{$invoice->total_amount}}</p></div>
    </div>
    <hr>
    <div class="row">
        <div class="col"><p>Picked up date: <span style="font-weight:bold">{{$prob->unit->picked_up_date}}</span></p></div>
        <div class="col"><p>Picked up by: <span style="font-weight:bold;">{{$prob->unit->picked_up_by}}</span></p></div>
    </div>
    </div>
</body>

</html><style>

    td{
        font-size:13px;
    }
</style>