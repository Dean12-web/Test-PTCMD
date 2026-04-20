<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function store(ApplicationRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $customer = Customer::firstOrCreate(
                    ['name' => $request->name],
                    ['income' => $request->income]
                );

                if ($customer->applications()->count() >= 3) {
                    throw new \RuntimeException('Nasabah sudah memiliki 3 pengajuan.');
                }

                $instalment = $request->nominal / $request->tenor;

                Application::create([
                    'customer_id' => $customer->id,
                    'application_type' => $request->application_type,
                    'nominal' => $request->nominal,
                    'monthly_installment' => $instalment,
                    'tenor' => $request->tenor,
                    'notes' => $request->notes,
                    'status' => Application::STATUS_PENDING,
                    'filling_date' => Carbon::now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan pembiayaan berhasil diajukan.',
            ], 201);
        } catch (\RuntimeException $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
            ], 422);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pengajuan.',
            ], 500);
        }
    }
}
