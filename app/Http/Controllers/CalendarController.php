<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $isAdmin = session('is_admin', false);

        $bookingsQuery = Bookings::with('activity', 'timeslot', 'institute');
        if (!$isAdmin) {
            $bookingsQuery->where('status', 1);
        }

        $bookings = $bookingsQuery->get();

        $events = $bookings->map(function ($booking) {
            $startTime = $booking->timeslot ? Carbon::createFromFormat('H:i:s', $booking->timeslot->start_time)->format('H:i') : null;
            $endTime = $booking->timeslot ? Carbon::createFromFormat('H:i:s', $booking->timeslot->end_time)->format('H:i') : null;

            $startDate = $booking->booking_date;
            $durationDays = $booking->activity->duration_days;
            $endDate = date('Y-m-d', strtotime("+$durationDays days", strtotime($startDate)));

            // ตรวจสอบวันที่สิ้นสุด และเพิ่ม 1 วันหากเป็นช่วงเวลาหลายวัน
            $endDate = $booking->end_date ?? $booking->booking_date;
            if ($endDate !== $booking->booking_date) {
                $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->addDay()->format('Y-m-d');
            }

            // คำนวณจำนวนผู้จองทั้งหมดในรอบนี้
            $totalApproved = Bookings::where('booking_date', $booking->booking_date)
                ->where('activity_id', $booking->activity_id)
                ->where('status', 1)
                ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty +  monk_qty'));
            // ถ้ามี timeslot ก็ให้คำนวณตาม timeslot
            if ($booking->timeslot && $booking->timeslot->timeslots_id) {
                $totalApproved = Bookings::where('booking_date', $booking->booking_date)
                    ->where('activity_id', $booking->activity_id)
                    ->where('timeslots_id', $booking->timeslot->timeslots_id)
                    ->where('status', 1)
                    ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty +  monk_qty'));
                }
            // คำนวณจำนวนคงเหลือจาก max_capacity
            if ($booking->activity->max_capacity !== null) {
                $remainingCapacity = $booking->activity->max_capacity - $totalApproved;
            } else {
                $remainingCapacity = 'ไม่จำกัดจำนวนคน';
            }
            return [
                'title' => $booking->activity->activity_name . " (สถานะการจอง: " . $this->getStatusText($booking->status) . ")",
                'start' => $booking->booking_date . ($startTime ? " $startTime" : ''),
                'end'   => $endDate . ($endTime ? " $endTime" : ''),
                'color' => $this->getStatusColor($booking->status),
                'extendedProps' => [
                    'start_time'  => $startTime,
                    'end_time'    => $endTime,
                    'duration_days' => $durationDays,
                    'status'      => $booking->status,
                    'visitor_name'  => $booking->visitor->visitorName ?? 'ไม่ระบุ',
                    'visitorEmail' => $booking->visitor->visitorEmail ?? 'ไม่ระบุ',
                    'tel' => $booking->visitor->tel ?? 'ไม่ระบุ',
                    'institute_name' => $booking->institute->instituteName ?? 'ไม่ระบุ',
                    'province' => $booking->institute->province,
                    'district' => $booking->institute->district,
                    'subdistrict' => $booking->institute->subdistrict,
                    'zipcode' => $booking->institute->zipcode,
                    'total_qty'     => $booking->children_qty + $booking->students_qty + $booking->adults_qty + $booking->disabled_qty + $booking->elderly_qty + $booking->monk_qty,
                    'remaining_capacity' => $remainingCapacity,
                ]
            ];
        });

        return response()->json($events);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            0 => '#ffc107',
            1 => '#28a745',
            2 => '#dc3545',
            default => '#000000',
        };
    }

    private function getStatusText($status)
    {
        return match ($status) {
            0 => 'รออนุมัติ',
            1 => 'อนุมัติ',
            2 => 'ยกเลิก',
            default => 'ไม่ทราบสถานะ',
        };
    }
}
