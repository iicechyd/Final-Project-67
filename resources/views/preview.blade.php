@extends('layouts.layout')
@section('title', 'เบิ่งบ่ ระบบจองเข้าชมพิพิธภัณฑ์')

<head>
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Maitree&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

@section('content')
    <div class="container">
        <div class="title p-5 text-center">
            <h1 style="color: #489085; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); 
">
                ระบบจองเข้าชมศูนย์พิพิธภัณฑ์</h1>
            <h2 style="color: #E6A732; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); 
">
                และแหล่งเรียนรู้ตลอดชีวิต <span style="color: #C06628;">มหาวิทยาลัยขอนแก่น</span>
            </h2>
            <a href="{{ url('/preview_general') }}" class="btn"
                style="background-color: #489085; color: #fff; font-size: 1.6rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); font-family: 'Noto Sans Thai', sans-serif; font-weight: 700;">+
                จองเข้าชมพิพิธภัณฑ์</a>
            <a href="{{ url('/preview_activity') }}" class="btn"
                style="background-color: #E6A732; color: #fff; font-size: 1.6rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); font-family: 'Noto Sans Thai', sans-serif; font-weight: 700;">+
                จองเข้าร่วมกิจกรรม</a>
            <div class="d-flex justify-content-center mt-2">
                <a href="{{ url('/checkBookingStatus') }}" class="btn"
                    style="background-color: #C06628; color: #fff; font-size: 1.6rem; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); font-family: 'Noto Sans Thai', sans-serif; font-weight: 700;">
                    ตรวจสอบสถานะการจอง</a>
            </div>
        </div>

    </div>
@endsection
