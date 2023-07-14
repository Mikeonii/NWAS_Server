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
  <div class="container" style="width:550px; margin:auto; margin-top:20px; ">

    <div class='row'>
        <div class="col">
            <h1 style="font-size:60px; color:black">JOB ORDER</h1>
            <div class="row">
                <div class="col"><h6 style="color:gray">No. JMBCJOS-{{$unit->id}}-23</h6></div>
                <div class="col" style="text-align:right"><h6 style="color:grey; ">Date: {{$unit->date_received}}</h6></div>
            </div>

            <div class="row" style="margin-top:20px;">
                <div class="col">
                    <h6 style="font-weight:bold">PREPA#000d85 FOR</h6>
                    <div
                    style="
                      height: 3px;
                      width: 100%;
                      background-image: linear-gradient(to right, #000d85, #000d85);
                    "
                  > </div>
                    <p>{{$unit->customer->customer_name}}</p>
                    <p style="margin-top:-20px;">P-{{$unit->customer->customer_purok}},{{$unit->customer->customer_barangay}}</p>
                    <p style="margin-top:-20px;">{{$unit->customer->customer_municipality}}, Surigao del Sur</p>
                    <p style="margin-top:-20px;">{{$unit->customer->customer_contact_no}}</p>
                </div>
                <div class="col">
                    <h6 style="font-weight:bold">UNIT INFORMATION</h6>
                    <div
                    style="
                      height: 3px;
                      width: 100%;
                      background-image: linear-gradient(to right, #000d85, #000d85);
                    "
                  > </div>
                    <p style="margin-top;">Type: {{$unit->unit_type}}</p>
                    <p style="margin-top:-20px;">Brand: {{$unit->unit_brand}}</p>
                    <p style="margin-top:-20px;">Model: {{$unit->unit_model}}</p>
                    <p style="margin-top:-20px;">S/N: {{$unit->serial_no}}</p>
                </div>
            
            </div>
            <div>
                <h6 style="font-weight:bold">INITIAL PROBLEM/S: @foreach($data as $d)<span>{{$d}}, </span>@endforeach </h6>
                <p>Terms and Conditions</p>
                <ol style="color:black; font-size:12px">
                    <li>Liability: Our business is not liable for any damages, external or internal, that may occur to your device after it has been removed from our shop, especially if the damage is not related to the service we rende#000d85.</li>
                    <li>Warranty: After the warranty period has expi#000d85, we are not obliged to repair or replace items for free. Additional charges may apply.</li> 
                    <li>Device Pick-up & Storage: You'll be notified of pick-up via text, email or chat after repair. If not picked up within 15 days, daily storage fee of 15 pesos applies. After 3 months, device becomes our property.</li>
                    <li>Payment: Diagnostic fee of 180 pesos for Laptop/Desktop External, 250 Internal, Printer diagnosis 300 pesos, Board diagnosis fee of 380 pesos, and other charges are due upon pick-up. We accept cash and online payments. Downpayments, if requi#000d85, must be paid before the order is processed.</li>
                    <li>Data Backup: Backup all important data before repair. We are not responsible for data loss.</li>
                    <li>Changes: These terms and conditions are subject to change at any time without notice.</li>
                    <li>Acceptance: By leaving your device for repair, you are accepting these terms and conditions.</li>
                </ol>
                <p>Signed: ______________________________</p>
            </div>
            <div class="row" style="">
                <p><em>Thank you for doing business with us!</em></p>
            </div>
        </div>
    </div>

</div>

</div>
</body>
</html>