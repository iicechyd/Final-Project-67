@extends('layouts.layout')
@section('title', 'จองกิจกรรมพิพิธภัณฑ์')

<head>
    <link rel="stylesheet" href="{{ asset('activity_detail.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

@section('content')
    <div class="container py-5">
        <div class="srow gy-4">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-6 col-md-12 px-3 py-2">
                            <div class="image "
                                style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                <img src="{{ asset('storage/' . $activity->image) }}" class="card-img-top"
                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                    alt="{{ $activity->activity_name }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card-body">
                                <h2 class="card-title">{{ $activity->activity_name }}</h2>
                                <p class="card-text text-muted">{{ $activity->description }}</p>
                                <div class="mt-3">
                                    <p>ราคา </p>
                                    <p>
                                        เด็ก: {{ $activity->children_price > 0 ? $activity->children_price . ' บาท' : 'ฟรี' }} | 
                                        นักเรียน/นักศึกษา: {{ $activity->student_price > 0 ? $activity->student_price . ' บาท' : 'ฟรี' }} | 
                                        ผู้ใหญ่: {{ $activity->adult_price > 0 ? $activity->adult_price . ' บาท' : 'ฟรี' }}
                                    </p>
                                    <p>
                                        ผู้พิการ: {{ $activity->disabled_price > 0 ? $activity->disabled_price . ' บาท' : 'ฟรี' }} | 
                                        ผู้สูงอายุ: {{ $activity->elderly_price > 0 ? $activity->elderly_price . ' บาท' : 'ฟรี' }} | 
                                        พระภิกษุสงฆ์ /สามเณร: {{ $activity->monk_price > 0 ? $activity->monk_price . ' บาท' : 'ฟรี' }}
                                    </p>                                    
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('form_bookings.activity', ['activity_id' => $activity->activity_id]) }}"
                                        class="btn text-white width:50%"
                                        style="background-color: #489085; font-family: 'Noto Sans Thai', sans-serif;">
                                        จองกิจกรรม
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 px-3 py-2">
                            <div class="bg-primary text-white text-center d-flex align-items-center justify-content-center"
                                style="width: 115px; height: 100px;">
                                <p>115 X 100</p>
                            </div>
                            <div class="bg-warning text-white text-center d-flex align-items-center justify-content-center"
                                style="width: 115px; height: 100px;">
                                <p>115 X 100</p>
                            </div>
                            <div class="bg-success text-white text-center d-flex align-items-center justify-content-center"
                                style="width: 115px; height: 100px;">
                                <p>115 X 100</p>
                            </div>
                            <div class="bg-info text-white text-center d-flex align-items-center justify-content-center"
                                style="width: 115px; height: 100px;">
                                <p>115 X 100</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection
