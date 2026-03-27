<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    public function index()
    {
        $subs = DB::table('submissions')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.submissions.index', compact('subs'));
    }

    public function create()
    {
        return view('dashboard.submissions.create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'title' => 'required|max:150',
            'description' => 'required',
            'amount' => 'required|numeric|min:1|max:999999999'
        ]);

        DB::table('submissions')->insert([
            'user_id' => auth()->id(),
            'title' => $req->title,
            'description' => $req->description,
            'amount' => $req->amount,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->route('submissions.index')
            ->with('success', 'Pengajuan berhasil dikirim');
    }

    public function show($id)
    {
        $submission = DB::table('submissions')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$submission) {
            return redirect()->route('submissions.index')
                ->with('error', 'Pengajuan tidak ditemukan');
        }

        $disbursement = DB::table('disbursements')
            ->where('submission_id', $submission->id)
            ->first();

        return view('dashboard.submissions.show', compact('submission', 'disbursement'));
    }

    public function createDisbursement($id)
    {
        $submission = DB::table('submissions')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$submission) {
            return redirect()->route('submissions.index')
                ->with('error', 'Pengajuan tidak ditemukan atau belum disetujui');
        }

        // Cek apakah sudah ada data pencairan - jika sudah ada, redirect ke show dengan info
        $existingDisbursement = DB::table('disbursements')
            ->where('submission_id', $id)
            ->first();

        if ($existingDisbursement) {
            return redirect()->route('submissions.show', $id)
                ->with('info', 'Data pencairan sudah dibuat sebelumnya');
        }

        return view('dashboard.submissions.disbursement', compact('submission'));
    }

    public function storeDisbursement(Request $req, $id)
    {
        $submission = DB::table('submissions')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$submission) {
            return redirect()->route('submissions.index')
                ->with('error', 'Pengajuan tidak ditemukan atau belum disetujui');
        }

        // Cek apakah sudah ada data pencairan
        $existingDisbursement = DB::table('disbursements')
            ->where('submission_id', $id)
            ->exists();

        if ($existingDisbursement) {
            return redirect()->route('submissions.show', $id)
                ->with('error', 'Data pencairan sudah dibuat');
        }

        $rules = [
            'method' => 'required|in:bank_transfer,e_wallet,cash',
            'account_name' => 'required|max:100',
            'account_number' => 'required|max:50',
            'notes' => 'nullable|max:500'
        ];

        if ($req->method == 'bank_transfer') {
            $rules['bank_name'] = 'required|max:100';
        }

        if ($req->method == 'e_wallet') {
            $rules['ewallet_type'] = 'required|max:50';
        }

        $req->validate($rules);

        DB::table('disbursements')->insert([
            'submission_id' => $id,
            'amount' => $submission->amount, // TAMBAHKAN INI
            'method' => $req->method,
            'account_name' => $req->account_name,
            'account_number' => $req->account_number,
            'bank_name' => $req->bank_name,
            'ewallet_type' => $req->ewallet_type,
            'notes' => $req->notes,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // Update status submission menjadi disbursed
        DB::table('submissions')
            ->where('id', $id)
            ->update(['status' => 'disbursed', 'updated_at' => now()]);

        return redirect()->route('submissions.show', $id)
            ->with('success', 'Data pencairan berhasil disimpan');
    }
}