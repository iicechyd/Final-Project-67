<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $today = Carbon::today();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $yearStart = $today->copy()->startOfYear();
        $yearEnd = $today->copy()->endOfYear();

        $activities = Activity::all();

        $totalVisitorsToday = [];
        foreach ($activities as $activity) {
            $totalVisitorsToday[$activity->activity_id] = Bookings::where('activity_id', $activity->activity_id)
                ->whereDate('booking_date', $today)
                ->where('status', 1)
                ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty + monk_qty'));
        }

        // จำนวนผู้เข้าชมทั้งหมดสำหรับแต่ละกิจกรรมในสัปดาห์นี้
    $totalVisitorsThisWeek = [];
    foreach ($activities as $activity) {
        $totalVisitorsThisWeek[$activity->activity_id] = Bookings::where('activity_id', $activity->activity_id)
            ->whereBetween('booking_date', [$weekStart, $weekEnd])
            ->where('status', 1)
            ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty + monk_qty'));
    }

    // จำนวนผู้เข้าชมทั้งหมดสำหรับแต่ละกิจกรรมในเดือนนี้
    $totalVisitorsThisMonth = [];
    foreach ($activities as $activity) {
        $totalVisitorsThisMonth[$activity->activity_id] = Bookings::where('activity_id', $activity->activity_id)
            ->whereBetween('booking_date', [$monthStart, $monthEnd])
            ->where('status', 1)
            ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty + monk_qty'));
    }

    $totalVisitorsThisYear = [];
    foreach ($activities as $activity) {
        $totalVisitorsThisYear[$activity->activity_id] = Bookings::where('activity_id', $activity->activity_id)
            ->whereBetween('booking_date', [$yearStart, $yearEnd])
            ->where('status', 1)
            ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty + monk_qty'));
    }

        $totalVisitors = [];
        foreach ($activities as $activity) {
            $totalVisitors[$activity->activity_id] = Bookings::where('activity_id', $activity->activity_id)
                ->where('status', 1)
                ->sum(DB::raw('children_qty + students_qty + adults_qty + disabled_qty + elderly_qty + monk_qty'));
        }

        $specialActivities = DB::table('activities')
            ->join('activity_types', 'activities.activity_type_id', '=', 'activity_types.activity_type_id')
            ->leftJoin('bookings', 'activities.activity_id', '=', 'bookings.activity_id')
            ->select(
                'activities.activity_name',
                DB::raw('
                SUM(
                    COALESCE(bookings.children_qty, 0) +
                    COALESCE(bookings.students_qty, 0) +
                    COALESCE(bookings.adults_qty, 0) +
                    COALESCE(bookings.disabled_qty, 0) +
                    COALESCE(bookings.elderly_qty, 0) +
                    COALESCE(bookings.monk_qty, 0)
                ) as total_visitors
            '),
                DB::raw('
                SUM(
                    COALESCE(bookings.children_qty, 0) * COALESCE(activities.children_price, 0) +
                    COALESCE(bookings.students_qty, 0) * COALESCE(activities.student_price, 0) +
                    COALESCE(bookings.adults_qty, 0) * COALESCE(activities.adult_price, 0) +
                    COALESCE(bookings.disabled_qty, 0) * COALESCE(activities.disabled_price, 0) +
                    COALESCE(bookings.elderly_qty, 0) * COALESCE(activities.elderly_price, 0) +
                    COALESCE(bookings.monk_qty, 0) * COALESCE(activities.monk_price, 0)
                ) as total_price
            ')
            )
            ->where('activity_types.activity_type_id', '=', 2)
            ->groupBy('activities.activity_id', 'activities.activity_name')
            ->get();

        // ดึงข้อมูลจำนวนผู้เข้าชมทั้งหมด
        $visitorStats = Bookings::selectRaw('
        SUM(children_qty) as children_qty, 
        SUM(students_qty) as students_qty, 
        SUM(adults_qty) as adults_qty, 
        SUM(disabled_qty) as disabled_qty, 
        SUM(elderly_qty) as elderly_qty, 
        SUM(monk_qty) as monk_qty
    ')
            ->first();

        return view('admin.dashboard', compact(
            'activities', 
            'totalVisitorsToday', 
            'totalVisitorsThisWeek',
            'totalVisitorsThisMonth',
            'totalVisitorsThisYear',
            'totalVisitors', 
            'specialActivities', 
            'visitorStats',
        ));
    }
}