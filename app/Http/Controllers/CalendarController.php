<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Bookings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->role->role_name === 'Admin';

        $bookingsQuery = Bookings::with('activity', 'timeslot', 'institute');
        if (!$isAdmin) {
            $bookingsQuery->where('status', 1);
        } else {
            $bookingsQuery->whereIn('status', [0, 1]);
        }

        $bookings = $bookingsQuery->get();

        $events = $isAdmin ? $this->getAdminEvents($bookings) : $this->getGroupedEvents($bookings);

        return response()->json($events);
    }

    private function getAdminEvents($bookings)
    {
        return $bookings->map(function ($booking) {
            $totalApproved = $this->calculateTotalApproved($booking);
            return $this->createEvent($booking, $totalApproved);
        });
    }

    private function getGroupedEvents($bookings)
    {
        $groupedBookings = $bookings->groupBy(function ($booking) {
            return $booking->booking_date . '-' . $booking->activity_id . '-' . ($booking->timeslot->timeslots_id ?? 'no_timeslot');
        });

        return $groupedBookings->map(function ($groupedBooking) {
            $firstBooking = $groupedBooking->first();
            $totalApproved = $this->calculateTotalApprovedForGroup($groupedBooking);
            return $this->createEvent($firstBooking, $totalApproved);
        })->values();
    }

    private function calculateTotalApprovedForGroup($groupedBookings)
{
    // Calculate the total approved for all bookings in the group
    return $groupedBookings->sum(function ($booking) {
        return $this->calculateTotalApproved($booking);
    });
}

    private function createEvent($booking, $totalApproved)
    {
        $startTime = $booking->timeslot ? Carbon::createFromFormat('H:i:s', $booking->timeslot->start_time)->format('H:i') : null;
        $endTime = $booking->timeslot ? Carbon::createFromFormat('H:i:s', $booking->timeslot->end_time)->format('H:i') : null;
        
        $startDate = Carbon::createFromFormat('Y-m-d', $booking->booking_date);
        $durationDays = $booking->activity->duration_days ?? 1;
        $endDate = $startDate->copy()->addDays($durationDays - 1)->format('Y-m-d');

        $remainingCapacity = $booking->activity->max_capacity !== null 
        ? max(0, $booking->activity->max_capacity - $totalApproved)
        : 'ไม่จำกัดจำนวนคน';
        
        return [
            'title' => $booking->activity->activity_name . " (สถานะการจอง: " . $this->getStatusText($booking->status) . ")",
            'start' => $startDate->format('Y-m-d') . ($startTime ? " $startTime" : ''),
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
                'total_qty'     => $totalApproved,
                'remaining_capacity' => $remainingCapacity,
            ]
        ];
    }

    private function calculateTotalApproved($booking)
    {
        return $booking->children_qty + $booking->students_qty + $booking->adults_qty + $booking->disabled_qty + $booking->elderly_qty + $booking->monk_qty;
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
