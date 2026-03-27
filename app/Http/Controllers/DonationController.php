<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DonationController extends Controller
{
    private $xenditApiKey    = 'xnd_development_19yz6xrd0lERlfsKhIwvoKZAS13FMnou6zoacedcm5ma1aKoL8UjOiLpYIjPd5';
    private $xenditPublicKey = 'xnd_public_development_ZoJbTFUqyA1iyVhVsH_dLVtA4qxchV9iP3wx5nBQYpvDlHSmNsvOfgjU_3SVKiF';

    // 1) Form pilih nominal
    public function create() 
    {
        return view('dashboard.donate');
    }

    // 2) Simpan donasi sebagai pending
    public function store(Request $req) 
    {
        $req->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        $donationId = DB::table('donations')->insertGetId([
            'user_id'        => auth()->id(),
            'amount'         => $req->amount,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'created_at'     => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()
            ->route('donate.payment', $donationId)
            ->with('success', 'Donasi berhasil dibuat. Silakan lakukan pembayaran.');
    }

    // 3) Daftar riwayat donasi
public function index()
{
    $donations = DB::table('donations')
        ->select('*') // atau spesifik: ->select('id', 'amount', 'status', 'payment_status', 'rejection_reason', 'created_at', 'paid_at')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('dashboard.donations.index', compact('donations'));
}

    // 4) Halaman pembayaran + tangani redirect success/failed
    public function payment(Request $request, $id)
    {
        $donation = DB::table('donations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$donation) {
            return redirect()
                ->route('donate.form')
                ->with('error', 'Donasi tidak ditemukan.');
        }

        // Jika user datang dengan ?payment=success
        if ($request->query('payment') === 'success') {
            // Verifikasi langsung ke Xendit (opsional tapi direkomendasikan)
            $resp = Http::withBasicAuth($this->xenditApiKey, '')
                        ->get("https://api.xendit.co/v2/invoices/{$donation->invoice_id}");
            $data = $resp->json();

            if (isset($data['status']) && $data['status'] === 'PAID') {
                if ($donation->payment_status !== 'paid') {
                    DB::table('donations')
                        ->where('id', $id)
                        ->update([
                            'payment_status' => 'paid',
                            'status'         => 'completed',
                            'paid_at'        => Carbon::now('Asia/Jakarta'),
                            'updated_at'     => Carbon::now('Asia/Jakarta'),
                        ]);
                }
                return redirect()
                    ->route('donations.index')
                    ->with('success', 'Pembayaran berhasil, terima kasih!');
            }

            // Jika belum benar-benar PAID
            return redirect()
                ->route('donate.payment', $id)
                ->with('error', 'Status pembayaran belum terkonfirmasi.');
        }

        // Jika datang dengan ?payment=failed
        if ($request->query('payment') === 'failed') {
            return redirect()
                ->route('donate.payment', $id)
                ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
        }

        // Normal show payment page
        if ($donation->payment_status === 'paid') {
            return redirect()
                ->route('donations.index')
                ->with('info', 'Donasi ini sudah dibayar.');
        }

        return view('dashboard.donate-payment', compact('donation'));
    }

    // 5) Generate invoice Xendit
    public function createInvoice(Request $req, $id)
    {
        $donation = DB::table('donations')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$donation) {
            return response()->json(['error' => 'Donasi tidak ditemukan'], 404);
        }

        $user = auth()->user();

        $invoiceData = [
            'external_id'         => 'donation_' . $id . '_' . time(),
            'payer_email'         => $user->email,
            'description'         => 'Donasi sebesar Rp ' . number_format($donation->amount, 0, ',', '.'),
            'amount'              => (int)$donation->amount,
            'currency'            => 'IDR',
            'invoice_duration'    => 86400,
            'success_redirect_url'=> route('donate.payment', $id) . '?payment=success',
            'failure_redirect_url'=> route('donate.payment', $id) . '?payment=failed',
            'customer'            => [
                'given_names' => $user->name,
                'email'       => $user->email,
            ],
            'items' => [
                [
                    'name'     => 'Donasi',
                    'quantity' => 1,
                    'price'    => (int)$donation->amount,
                    'category' => 'Donation',
                ]
            ],
        ];

        try {
            $resp = Http::withBasicAuth($this->xenditApiKey, '')
                        ->post('https://api.xendit.co/v2/invoices', $invoiceData);

            if ($resp->successful()) {
                $invoice = $resp->json();
                DB::table('donations')
                    ->where('id', $id)
                    ->update([
                        'invoice_id'  => $invoice['id'],
                        'external_id' => $invoice['external_id'],
                        'updated_at'  => Carbon::now('Asia/Jakarta'),
                    ]);

                return response()->json([
                    'success'     => true,
                    'invoice_url' => $invoice['invoice_url'],
                ]);
            }

            Log::error('Xendit Invoice Error:', $resp->json());
            return response()->json(['error' => 'Gagal membuat invoice.'], 500);

        } catch (\Exception $e) {
            Log::error('Xendit Exception:', ['msg' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    // 6) (Optional) webhook method tetap bisa dipakai
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Xendit Webhook:', $payload);

        if (isset($payload['status']) && $payload['status'] === 'PAID') {
            $donation = DB::table('donations')
                ->where('external_id', $payload['external_id'])
                ->first();

            if ($donation) {
                DB::table('donations')
                    ->where('id', $donation->id)
                    ->update([
                        'payment_status' => 'paid',
                        'status'         => 'completed',
                        'paid_at'        => Carbon::now('Asia/Jakarta'),
                        'updated_at'     => Carbon::now('Asia/Jakarta'),
                    ]);
                Log::info("Payment successful for donation ID {$donation->id}");
            }
        }

        return response()->json(['success' => true]);
    }
}
