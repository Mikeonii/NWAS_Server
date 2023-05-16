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
            <h1 style="font-size:60px; color:black">PAYSLIP</h1>

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
<!-- CONTENT -->
<div class = "container" style="width:550px; margin-auto;">
  
   
    </div>
 
</div>
<div class="container" style="text-align:left; margin-top:50px; width:600px;margin:auto">
   <h3>Name: {{$employee->name}}</h3>
   <h3>Position: {{$employee->position}}</h3>
   <h3>Daily Rate: {{$employee->daily_rate}}</h3>
</div>
<hr>
<h4 style="font-weight:bold; color:grey">PAYSLIP INFORMATION: </h4>
<h5>Wage for the month of: {{$wage->month}}, {{$wage->year}} {{$wage->half}}</h5>
<h5>Total Days Worked: {{$wage->total_days_worked}}</h5>
<h5>Total Gross: {{$wage->total_gross}}</h5>
<h5>Deductions: {{$wage->deductions}}</h5>
<h5>Notes: {{$wage->notes}}</h5>
<hr>
<h4 style='color:green; font-weight-bold;'>Total Net: <strong>{{$wage->total_net}}</strong></h4>
<p>Acknowledged and recived by: ______________________________</p>
<p>Prepared by: _____________________________</p>

<p style="font-size:14px; color:grey">JMBComputers | www.jmbcomputers.com | facebook.com/JMBComputersN</p>
</div>
</body>
</html>