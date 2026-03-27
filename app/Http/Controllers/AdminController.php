<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
      public function donations()
    {
        $allDonasi = DB::table('donations')
            ->leftJoin('users', 'donations.user_id', '=', 'users.id')
            ->select('donations.*', 'users.name as user_name')
            ->orderBy('donations.created_at', 'desc')
            ->paginate(10);
        return view('admin.donations.index', compact('allDonasi'));
    }

    // NEW: Method untuk approve donasi
    public function approveDonation($id)
    {
        DB::beginTransaction();
        
        try {
            DB::table('donations')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now()
            ]);
            
            DB::commit();
            return back()->with('success', 'Donasi berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui donasi');
        }
    }

    // NEW: Method untuk reject donasi
    public function rejectDonation(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|max:500'
        ]);

        DB::beginTransaction();
        
        try {
            DB::table('donations')->where('id', $id)->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'updated_at' => now()
            ]);
            
            DB::commit();
            return back()->with('success', 'Donasi berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menolak donasi');
        }
    }
    public function submissions()
    {
        $allSub = DB::table('submissions')
            ->leftJoin('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.name as user_name')
            ->orderBy('submissions.created_at', 'desc')
            ->paginate(10);
            
        return view('admin.submissions.index', compact('allSub'));
    }

    public function showSubmission($id)
    {
        $submission = DB::table('submissions')
            ->leftJoin('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.name as user_name', 'users.email as user_email')
            ->where('submissions.id', $id)
            ->first();

        if (!$submission) {
            return redirect()->route('admin.submissions')
                ->with('error', 'Pengajuan tidak ditemukan');
        }

        $disbursement = DB::table('disbursements')
            ->where('submission_id', $id)
            ->first();

        return view('admin.submissions.show', compact('submission', 'disbursement'));
    }

public function approveSubmission(Request $req, $id)
    {
        DB::beginTransaction();
        
        try {
            $submission = DB::table('submissions')->where('id', $id)->first();
            
            if (!$submission) {
                DB::rollback();
                return back()->with('error', 'Pengajuan tidak ditemukan');
            }
            
            if ($submission->status !== 'pending') {
                DB::rollback();
                return back()->with('error', 'Pengajuan sudah diproses sebelumnya');
            }
            
            // HANYA update status submission ke approved
            DB::table('submissions')->where('id', $id)->update([
                'status' => 'approved',
                'approved_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            return back()->with('success', 'Pengajuan berhasil disetujui. User dapat melakukan pencairan.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectSubmission(Request $req, $id)
    {
        $req->validate([
            'rejection_reason' => 'required|max:500'
        ]);

        DB::table('submissions')->where('id', $id)->update([
            'status' => 'rejected',
            'rejection_reason' => $req->rejection_reason,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak');
    }

    // Halaman untuk mengelola pencairan
    public function disbursements()
    {
        $disbursements = DB::table('disbursements')
            ->leftJoin('submissions', 'disbursements.submission_id', '=', 'submissions.id')
            ->leftJoin('users', 'submissions.user_id', '=', 'users.id')
            ->select(
                'disbursements.*',
                'submissions.title as submission_title',
                'submissions.amount as submission_amount',
                'users.name as user_name'
            )
            ->orderBy('disbursements.created_at', 'desc')
            ->paginate(10);

        // PERBAIKAN: Ubah ke view admin.disbursements.index
        return view('admin.disbursements.index', compact('disbursements'));
    }

    public function processDisbursement($id)
    {
        DB::table('disbursements')->where('id', $id)->update([
            'status' => 'processing',
            'processed_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pencairan sedang diproses');
    }

    public function completeDisbursement($id)
    {
        DB::table('disbursements')->where('id', $id)->update([
            'status' => 'completed',
            'completed_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pencairan berhasil diselesaikan');
    }

    public function failDisbursement(Request $req, $id)
    {
        $req->validate([
            'failure_reason' => 'required|max:500'
        ]);

        DB::table('disbursements')->where('id', $id)->update([
            'status' => 'failed',
            'notes' => $req->failure_reason,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Status pencairan diubah menjadi gagal');
    }
    public function users()
    {
        $users = DB::table('users')
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }

    public function editUser($id)
    {
        $user = DB::table('users')->where('id', $id)->where('role', 'user')->first();
        
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User tidak ditemukan');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->where('role', 'user')->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        // Check if user has donations or submissions
        $hasDonations = DB::table('donations')->where('user_id', $id)->exists();
        $hasSubmissions = DB::table('submissions')->where('user_id', $id)->exists();
        
        if ($hasDonations || $hasSubmissions) {
            return back()->with('error', 'User tidak dapat dihapus karena memiliki riwayat donasi atau pengajuan');
        }

        DB::table('users')->where('id', $id)->where('role', 'user')->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    // PENGELOLAAN DANA FUNCTIONS
    public function finance()
    {
        // Total Pemasukan (donations completed)
        $totalIncome = DB::table('donations')
            ->where('status', 'completed')
            ->sum('amount');

        // Total Pengeluaran (submissions disbursed)
        $totalExpense = DB::table('submissions')
            ->where('status', 'disbursed')
            ->sum('amount');

        // Saldo
        $balance = $totalIncome - $totalExpense;

        // Data Pemasukan (donations completed)
        $incomeData = DB::table('donations')
            ->leftJoin('users', 'donations.user_id', '=', 'users.id')
            ->select(
                'donations.id',
                'donations.amount',
                'donations.created_at',
                'donations.paid_at',
                'users.name as user_name'
            )
            ->where('donations.status', 'completed')
            ->orderBy('donations.paid_at', 'desc')
            ->paginate(10, ['*'], 'income_page');

        // Data Pengeluaran (submissions disbursed)
        $expenseData = DB::table('submissions')
            ->leftJoin('users', 'submissions.user_id', '=', 'users.id')
            ->select(
                'submissions.id',
                'submissions.title',
                'submissions.amount',
                'submissions.approved_at',
                'users.name as user_name'
            )
            ->where('submissions.status', 'disbursed')
            ->orderBy('submissions.approved_at', 'desc')
            ->paginate(10, ['*'], 'expense_page');

        // Statistik bulanan
        $monthlyStats = DB::select("
            SELECT 
                DATE_FORMAT(paid_at, '%Y-%m') as month,
                SUM(amount) as total_income,
                COUNT(*) as count_donations
            FROM donations 
            WHERE status = 'completed' 
            AND paid_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(paid_at, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12
        ");

        return view('admin.finance.index', compact(
            'totalIncome', 
            'totalExpense', 
            'balance', 
            'incomeData', 
            'expenseData',
            'monthlyStats'
        ));
    }
}