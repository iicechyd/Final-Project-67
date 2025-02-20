@extends('layouts.layout')
@section('title', 'ยกเลิกการจองเข้าชม')
@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('css/checkbookingstatus.css') }}">
    </head>

    <div class="container pt-5">
        <h2 class="text-2xl text-center font-bold" style="color: #e61212;">ยกเลิกการจองเข้าชม</h2>
        @if ($booking->status == 3)
            <p class="text-center mb-3 ">ยกเลิกการจองสำเร็จ</p>
        @else
            <p class="text-center mb-3">คุณต้องการยกเลิกการจองนี้หรือไม่?</p>
        @endif
        @component('components.table_checkbookings')
            <tr>
                <td>{{ $booking->activity->activity_name }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->locale('th')->translatedFormat('j F') }}
                    {{ \Carbon\Carbon::parse($booking->booking_date)->addYears(543)->year }}
                </td>
                <td>
                    @if ($booking->timeslot)
                        {{ \Carbon\Carbon::parse($booking->timeslot->start_time)->format('H:i') }} น. -
                        {{ \Carbon\Carbon::parse($booking->timeslot->end_time)->format('H:i') }} น.
                    @else
                    -
                    @endif
                </td>
                <td>
                    <a href="#detailsModal_{{ $booking->booking_id }}" class="text-blue-500 no-underline" data-bs-toggle="modal">
                        รายละเอียด
                    </a>
                </td>
                <td>{{ $booking->children_qty + $booking->students_qty + $booking->adults_qty + $booking->kid_qty + $booking->disabled_qty + $booking->elderly_qty + $booking->monk_qty }}
                    คน</td>
                <td>
                    @switch($booking->status)
                        @case(0)
                            <button type="button" class="status-btn-request">รออนุมัติ</button>
                        @break

                        @case(1)
                            <button type="button" class="status-btn-approved">อนุมัติ</button>
                        @break

                        @case(2)
                            <button type="button" class="status-btn-approved">เข้าชม</button>
                        @break

                        @case(3)
                            <button type="button" class="status-btn-except">ยกเลิก</button>
                        @break
                    @endswitch
                </td>
            </tr>
        @endcomponent
        <!-- Modal สำหรับแสดงรายละเอียด -->
        <div class="modal fade" id="detailsModal_{{ $booking->booking_id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">รายละเอียดการจอง -
                            {{ $booking->activity->activity_name }}</h5>
                    </div>
                    <div class="modal-body">
                        @if (!$booking->subActivities->isEmpty())
                            <p><strong>หลักสูตร:</strong>
                                {{ $booking->subActivities->pluck('sub_activity_name')->implode(', ') }}
                            </p>
                        @endif
                        <p><strong>ชื่อหน่วยงาน: </strong>{{ $booking->institute->instituteName }}</p>
                        <p><strong>ที่อยู่หน่วยงาน: </strong>{{ $booking->institute->instituteAddress }}
                            {{ $booking->institute->subdistrict }} {{ $booking->institute->district }}
                            {{ $booking->institute->inputProvince }} {{ $booking->institute->zipcode }}</p>
                        <p><strong>ชื่อผู้ประสานงาน: </strong>{{ $booking->visitor->visitorName }}</p>
                        <p><strong>อีเมลผู้ประสานงาน: </strong>{{ $booking->visitor->visitorEmail }}</p>
                        <p><strong>เบอร์โทรศัพท์: </strong>{{ $booking->visitor->tel }}</p>
                        @if ($booking->children_qty > 0)
                            <p>เด็ก : {{ $booking->children_qty }} คน</p>
                        @endif

                        @if ($booking->students_qty > 0)
                            <p>นร / นศ : {{ $booking->students_qty }} คน</p>
                        @endif

                        @if ($booking->adults_qty > 0)
                            <p><strong>ผู้ใหญ่ / คุณครู : </strong>{{ $booking->adults_qty }} คน</p>
                        @endif

                        @if ($booking->kid_qty > 0)
                            <p>เด็กเล็ก : {{ $booking->kid_qty }} คน</p>
                        @endif

                        @if ($booking->disabled_qty > 0)
                            <p>ผู้พิการ : {{ $booking->disabled_qty }} คน</p>
                        @endif

                        @if ($booking->elderly_qty > 0)
                            <p>ผู้สูงอายุ : {{ $booking->elderly_qty }} คน</p>
                        @endif

                        @if ($booking->monk_qty > 0)
                            <p>พระภิกษุสงฆ์ / สามเณร : {{ $booking->monk_qty }} รูป</p>
                        @endif
                        @if (!empty($booking->note))
                            <p>*หมายเหตุ: {{ $booking->note }}</p>
                        @endif
                        <p><strong>ยอดรวมราคา:</strong> {{ number_format($totalPrice, 2) }} บาท</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
        @if ($booking->status != 3)
            <div class="d-flex justify-content-center mt-4">
                <form action="{{ route('bookings.cancel.confirm', $booking->booking_id) }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        ยืนยันการยกเลิก
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Modal ยืนยันการยกเลิก -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">ยืนยันการยกเลิก</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center text-center">
                    คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการจองนี้?
                </div>
                <div class="modal-footer">
                    <form id="cancelBookingForm" action="{{ route('bookings.cancel.confirm', $booking->booking_id) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal -->
    @if (session('showSuccessModal'))
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">การยกเลิกสำเร็จ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ยกเลิกการจองของคุณเสร็จสมบูรณ์แล้ว
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('successModal'));
            myModal.show();
        </script>
    @endif
@endsection
