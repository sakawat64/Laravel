<?php
public function appointment_list(Request $request)
    {
        $appointments = Appointment::with('user','doctor')->get();
        //return json_encode($appointments);
       // $data = array();
       $result = array();
        foreach($appointments as $appointment)
        {
            $category_name = Category::find($appointment->doctor->category_id); //multiple data from sinle id
            $data = array(
                "user_name" => $appointment->user->name,
                "doctor_name" => $appointment->doctor->doctor_name,
                "date" => $appointment->appointment_date,
                "phone" => $appointment->doctor->phone_number,
                "category_name" => $category_name->category_name,
            );
            array_push($result,$data);
        }
        //return json_encode($result);
        return view('appointment.appointmentlist')->with('appointments',$result);
    }

    ?>

    <!-- In blade view -->
@extends('layouts.app')
@section('content')
<table class="table table-hover table-bordered results">
  <thead>
    <tr>
      <th class="col-xs-2">Patient Name</th>
      <th class="col-xs-2">Doctor Name</th>
      <th class="col-xs-2">Speciality</th>
      <th class="col-xs-2">Appointment Date</th>
      <th class="col-xs-2">Doctor Phone Number</th>
    </tr>
  </thead>
  <tbody>

  @foreach($appointments as $appointment)

    <tr>
      <td>{{$appointment['user_name']}}</td>
      <td>{{$appointment['doctor_name']}}</td>
      <td>{{$appointment['category_name']}}</td>
      <td>{{$appointment['date']}}</td>
      <td>{{$appointment['phone']}}</td>
    </tr>

    @endforeach
    
  </tbody>
</table>
@endsection