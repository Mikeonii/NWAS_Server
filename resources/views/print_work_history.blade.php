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
    <div class="" style="padding:10px;">
        <div class='row' style="margin-bottom:-20px;">
            <div class="col-7">
                <h4 style="font-size:30px; color:black; font-weight:bold; margin-top:8px;">WORK SUMMARY</h4>
                <div class="row">
                    {{-- <div class="col"><h6 style="color:gray">No. {{$ode}}</h6></div>
                    <div class="col" style="text-align:right"><h6 style="color:grey; ">Date: {{$ate}}</h6></div> --}}
                </div>
            </div>
            <div class="col-4"><img src="{{ asset('jmb_logo.png') }}" 
                width="90%" style="margin-top:-10px;">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <p>Customer Name: <span style="font-weight: bold">{{$problem->customer->customer_name}}</span></p>
                <p style="margin-top:-10px;">Laptop Brand/Model: <span style="font-weight: bold">{{$problem->unit->unit_brand}}-{{$problem->unit->unit_model}}</span></p>
                <p style="margin-top:-10px;">Initial Problem: <span style="font-weight: bold">{{$problem->problem_description}}</span></p>
            </div>
            <div class="col">
                <p>Status: <span style="font-weight: bold">_____________________________</span></p>
                <p style="margin-top:-10px;">Technician: <span style="font-weight: bold">_____________________________</span></p>
                <p style="margin-top:-10px;">Repair Date: <span style="font-weight: bold">_____________________________</span></p>
            </div>
        </div>
        <div class="row">
            <div class="col" style="  border: 1px solid #969696;  width: 120px;
            height: 400px;">
                <h4>ACTIONS</h4>
            </div>
            <div class="col" style="  border: 1px solid #969696;  width: 120px;
            height: 400px;">
                <h4>RESULTS</h4>
            </div>
            <div class="col" style="  border: 1px solid #969696;  width: 120px;
            height: 400px;">
                <h4>REMARKS</h4>
            </div>
        </div>
  

</div>
</body>
</html>