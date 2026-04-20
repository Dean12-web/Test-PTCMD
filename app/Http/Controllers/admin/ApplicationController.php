<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with('customer')->latest()->get();
        return view('admin.index', compact('applications'));
    }

    public function view(Request $request)
    {
        $query = Application::query()
            ->with('customer')
            ->leftJoin('customers', 'customers.id', '=', 'applications.customer_id')
            ->select('applications.*');

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('customers.name', 'like', '%' . $search . '%')
                    ->orWhere('applications.application_type', 'like', '%' . $search . '%')
                    ->orWhere('applications.tenor', 'like', '%' . $search . '%')
                    ->orWhere('applications.status', 'like', '%' . $search . '%');
            });
        }

        $allowedSorts = [
            'name' => 'customers.name',
            'application_type' => 'applications.application_type',
            'tenor' => 'applications.tenor',
            'status' => 'applications.status',
            'created_at' => 'applications.created_at',
            'filling_date' => 'applications.filling_date',
        ];
        $sort = $request->get('sort', 'created_at');
        $sortColumn = $allowedSorts[$sort] ?? 'applications.created_at';
        $direction = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 20], true)) {
            $perPage = 10;
        }

        $query->orderBy($sortColumn, $direction);

        $applications = $query->paginate($perPage);

        return response()->json([
            'rows' => $applications->map(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->customer?->name ?? '-',
                    'application_type' => $application->application_type,
                    'nominal' => $application->nominal,
                    'monthly_installment' => $application->monthly_installment,
                    'tenor' => $application->tenor,
                    'status' => $application->status,
                    'pendapatan' => $application->customer?->income ?? 0,
                    'notes' => $application->notes,
                    'tanggal' => Carbon::parse($application->filling_date)->format('d M Y')
                ];
            }),
            'pagination' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
                'from' => $applications->firstItem(),
                'to' => $applications->lastItem(),
            ]
        ]);
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

    public function approve($id)
    {
        try {
            $data = Application::findOrFail($id);

            if ($data->status !== Application::STATUS_PENDING) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan berstatus pending yang bisa disetujui.',
                ], 422);
            }

            $data->status = Application::STATUS_APPROVED;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil disetujui.',
            ]);
        } catch (ModelNotFoundException $error) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan.',
            ], 404);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pengajuan.',
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $data = Application::findOrFail($id);

            if ($data->status !== Application::STATUS_PENDING) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengajuan berstatus pending yang bisa ditolak.',
                ], 422);
            }

            $data->status = Application::STATUS_REJECTED;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak.',
            ]);
        } catch (ModelNotFoundException $error) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan.',
            ], 404);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak pengajuan.',
            ], 500);
        }
    }
}
