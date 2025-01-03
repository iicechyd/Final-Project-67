@extends('layouts.layout_admin')
@section('title', 'อนุมัติการจองเข้าชมพิพิธภัณฑ์')
@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('css/approved_bookings.css') }}">
    </head>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        <div class="button pb-2">
            <a href="{{ url('/admin/request_bookings/activity') }}" class="btn-request-outline">รออนุมัติ</a>
            <a href="{{ url('/admin/approved_bookings/activity') }}" class="btn btn-success">อนุมัติ</a>
            <a href="{{ url('/admin/except_cases_bookings/activity') }}" class="btn-except-outline">ยกเลิก</a>
        </div>

        <div class="form col-6">
            <form method="GET" action="{{ route('approved_bookings.activity') }}">
                <label for="activity_id">เลือกกิจกรรม:</label>
                <select name="activity_id" id="activity_id" class="form-select" onchange="this.form.submit()">
                    <option value="">กรุณาเลือกประเภทการเข้าชม</option>
                    @foreach ($activities as $activity)
                        <option value="{{ $activity->activity_id }}"
                            {{ request('activity_id') == $activity->activity_id ? 'selected' : '' }}>
                            {{ $activity->activity_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if (request('activity_id'))
            @php
                $selectedActivityName =
                    $activities->firstWhere('activity_id', request('activity_id'))->activity_name ?? null;
            @endphp

            @if (count($approvedBookings) > 0)
                <h1 class="table-heading text-center">{{ $selectedActivityName }}</h1>
                {{ $approvedBookings->appends(request()->query())->links() }}

                @component('components.table_approved_bookings')
                    @foreach ($approvedBookings as $item)
                        <tr>
                            <td>{{ $item->booking_id }}</td>
                            <td class="custom-td">
                                {{ \Carbon\Carbon::parse($item->booking_date)->locale('th')->translatedFormat('j F') }}
                                {{ \Carbon\Carbon::parse($item->booking_date)->addYears(543)->year }}
                            </td>
                            <td>
                                @if ($item->timeslot)
                                    {{ \Carbon\Carbon::parse($item->timeslot->start_time)->format('H:i') }} น. -
                                    {{ \Carbon\Carbon::parse($item->timeslot->end_time)->format('H:i') }} น.
                                @else
                                    ไม่มีรอบการเข้าชม
                                @endif
                            </td>
                            <td>
                                @if ($item->activity->max_capacity !== null)
                                    @if ($item->remaining_capacity > 0)
                                        {{ $item->remaining_capacity }} / {{ $item->activity->max_capacity }} คน
                                    @else
                                        รอบการเข้าชมนี้เต็มแล้ว
                                    @endif
                                @else
                                    ไม่จำกัดจำนวนคน
                                @endif
                            </td>
                            <td>
                                @switch($item->status)
                                    @case(0)
                                        <button type="button" class="btn btn-warning text-white">รออนุมัติ</button>
                                    @break

                                    @case(1)
                                        <button type="button" class="status-btn">อนุมัติ</button>
                                    @break

                                    @case(2)
                                        <button type="button" class="status-btn-except">ยกเลิก</button>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                <form action="{{ route('bookings.updateStatus', $item->booking_id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <div class="flex items-center space-x-3">
                                        <select name="status" id="statusSelect_{{ $item->booking_id }}"
                                            onchange="toggleCommentsField({{ $item->booking_id }})"
                                            class="bg-gray-100 border border-gray-300 rounded-lg p-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="checkin" {{ $item->status == 2 ? 'selected' : '' }}>เข้าชม</option>
                                            <option value="cancel" {{ $item->status == 3 ? 'selected' : '' }}>ยกเลิก</option>
                                        </select>
                                        <!-- ฟิลด์จำนวนผู้เข้าชม -->
                                        <div id="visitorsField_{{ $item->booking_id }}" class="visitors-field pt-2"
                                            style="display: {{ $item->status == 2 ? 'block' : 'none' }};">
                                            <input type="number" name="number_of_visitors" placeholder="ระบุจำนวนผู้เข้าชม"
                                                class="bg-gray-100 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                        <!-- ฟิลด์ความคิดเห็น -->
                                        <div id="commentsField_{{ $item->booking_id }}" class="comments-field"
                                            style="display: {{ $item->status == 3 ? 'block' : 'none' }};">
                                            <input type="text" name="comments" placeholder="กรอกเหตุผล"
                                                class="bg-gray-100 border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                        <button type="submit" class="button-custom">
                                            อัปเดตสถานะ
                                        </button>
                                        
                                    </div>
                                </form>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info text-white" data-toggle="modal"
                                    data-target="#detailsModal_{{ $item->booking_id }}">
                                    รายละเอียด
                                </button>
                                @if ($item->documents->isNotEmpty())
                                    <p class="text-success pt-2">แนบไฟล์เอกสารเรียบร้อย</p>
                                @else
                                    <p class="text-danger pt-2">รอแนบเอกสาร</p>
                                @endif
                            </td>
                            <!-- Modal สำหรับแสดงรายละเอียด -->
                            <div class="modal fade" id="detailsModal_{{ $item->booking_id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">รายละเอียดการจอง -
                                                {{ $item->activity->activity_name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>วันเวลาที่จองเข้ามา:
                                                </strong>{{ \Carbon\Carbon::parse($item->created_at)->locale('th')->translatedFormat('j F') }}
                                                {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }} เวลา
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} น.</p>
                                            <p><strong>ชื่อหน่วยงาน: </strong>{{ $item->institute->instituteName }}</p>
                                            <p><strong>ที่อยู่หน่วยงาน: </strong>{{ $item->institute->instituteAddress }}
                                                {{ $item->institute->subdistrict }} {{ $item->institute->district }}
                                                {{ $item->institute->inputProvince }} {{ $item->institute->zipcode }}</p>
                                            <p><strong>ชื่อผู้ประสานงาน: </strong>{{ $item->visitor->visitorName }}</p>
                                            <p><strong>อีเมลผู้ประสานงาน: </strong>{{ $item->visitor->visitorEmail }}</p>
                                            <p><strong>เบอร์โทรศัพท์: </strong>{{ $item->visitor->tel }}</p>
                                            <p><strong>เด็ก (คน):
                                                </strong>{{ $item->children_qty > 0 ? $item->children_qty . ' คน' : '-' }}</p>
                                            <p><strong>นร / นศ (คน):
                                                </strong>{{ $item->students_qty > 0 ? $item->students_qty . ' คน' : '-' }}</p>
                                            <p><strong>ผู้ใหญ่ / คุณครู (คน):
                                                </strong>{{ $item->adults_qty > 0 ? $item->adults_qty . ' คน' : '-' }}</p>
                                            <p><strong>ผู้พิการ (คน):
                                                </strong>{{ $item->disabled_qty > 0 ? $item->disabled_qty . ' คน' : '-' }}</p>
                                            <p><strong>ผู้สูงอายุ (คน):
                                                </strong>{{ $item->elderly_qty > 0 ? $item->elderly_qty . ' คน' : '-' }}</p>
                                            <p><strong>พระภิกษุสงฆ์ / สามเณร (คน):
                                                </strong>{{ $item->monk_qty > 0 ? $item->monk_qty . ' รูป' : '-' }}</p>
                                            <p><strong>จำนวนคนทั้งหมด:
                                                </strong>{{ $item->children_qty + $item->students_qty + $item->adults_qty + $item->disabled_qty + $item->elderly_qty + $item->monk_qty }}
                                                คน</p>
                                            <p><strong>ยอดรวมราคา: </strong>{{ number_format($item->totalPrice, 2) }} บาท</p>
                                            <p><strong>แก้ไขสถานะ: </strong>
                                                @if ($item->latestStatusChange)
                                                {{ \Carbon\Carbon::parse($item->latestStatusChange->updated_at)->locale('th')->translatedFormat('j F') }}
                                                {{ \Carbon\Carbon::parse($item->latestStatusChange->updated_at)->year + 543 }}
                                                เวลา
                                                {{ \Carbon\Carbon::parse($item->latestStatusChange->updated_at)->format('H:i') }}
                                                น.
                                                แก้ไขโดยเจ้าหน้าที่: {{ $item->latestStatusChange->changed_by ?? 'N/A' }}
                                                @else
                                                ไม่พบข้อมูลการเปลี่ยนแปลงสถานะ
                                            @endif
                                            </p>
                                            <p><strong>แนบเอกสาร: </strong>
                                                @if ($item->documents->isNotEmpty())
                                                    @foreach ($item->documents as $document)
                                                        <span class="mr-2">
                                                            <a href="{{ asset('storage/' . $document->file_path) }}"
                                                                target="_blank">
                                                                {{ $document->file_name }}
                                                            </a>
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-danger">รอแนบเอกสาร</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                @endcomponent
            @else
                <h2 class="text-center py-5">ไม่พบข้อมูลการจองสำหรับกิจกรรมนี้</h2>
            @endif
    </div>
@else
    <h1 class="text text-center py-5 ">กรุณาเลือกกิจกรรมเพื่อตรวจสอบข้อมูล</h1>
    @endif
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/approved_bookings.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var approvedBookings = @json($approvedBookings->pluck('booking_id'));
        approvedBookings.forEach(function(booking_id) {
            toggleCommentsField(booking_id);
        });
    });

    function toggleCommentsField(booking_id) {
        var status = document.getElementById("statusSelect_" + booking_id).value;
        var commentsField = document.getElementById("commentsField_" + booking_id);
        var visitorsField = document.getElementById("visitorsField_" + booking_id);

        if (status === "checkin") {
            commentsField.style.display = "none";
            visitorsField.style.display = "block";
        } else if (status === "cancel") {
            commentsField.style.display = "block";
            visitorsField.style.display = "none";
        } else {
            commentsField.style.display = "none";
            visitorsField.style.display = "none";
        }
    }
</script>
