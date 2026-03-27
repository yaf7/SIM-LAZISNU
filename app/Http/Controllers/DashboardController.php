<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Total nominal donasi yang sudah approved
        $totalDonasi = DB::table('donations')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        // Total transaksi (all)
        $totalTransaksi = DB::table('donations')
            ->where('user_id', $user->id)
            ->count();

        // Menunggu pembayaran
        $pending = DB::table('donations')
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->count();

        // Sukses bayar
        $paid = DB::table('donations')
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->count();

        // 5 donasi terakhir
        $recentDonations = DB::table('donations')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get([
                'id', 'amount', 'status', 'payment_status', 'created_at', 'paid_at'
            ]);

        return view('dashboard.home', compact(
            'totalDonasi',
            'totalTransaksi',
            'pending',
            'paid',
            'recentDonations'
        ));
    }
public function report()
{
    $userId = auth()->id();

    // Semua donation + submission
    $donations = \DB::table('donations')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();
    $subs = \DB::table('submissions')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    // Statistik ringkas
    $stats = [
        'total_donasi'    => $donations->sum('amount'),
        'count_donasi'    => $donations->count(),
        'approved_donasi' => $donations->where('status','approved')->count(),
        'pending_donasi'  => $donations->where('status','pending')->count(),
        'total_subs'      => $subs->count(),
        'approved_subs'   => $subs->where('status','approved')->count(),
        'rejected_subs'   => $subs->where('status','rejected')->count(),
    ];

    // Trend bulanan donasi (6 bulan terakhir)
    $monthly = \DB::table('donations')
        ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(amount) as total")
        ->where('user_id',$userId)
        ->where('created_at','>=', now()->subMonths(5)->startOfMonth())
        ->groupBy('ym')
        ->orderBy('ym')
        ->get()
        ->map(fn($r) => ['month'=> $r->ym, 'total'=> (int)$r->total]);

    return view('dashboard.report', compact('donations','subs','stats','monthly'));
}

}
