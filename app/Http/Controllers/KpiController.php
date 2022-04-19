<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KpiController extends Controller
{
    public function getAllKpi()
    {
        $kpi = Kpi::all();

        $respond = [
            'status' => '200',
            'message' => 'All Kpis',
            'data' => $kpi
        ];
        return $respond;
    }

    public function getKpiById($id)
    {
        $kpi = Kpi::find($id);

        if (isset($kpi)) {

            $respond = [
                'status' => 200,
                'message' => 'Kpi found',
                'data' => $kpi
            ];
            return $respond;
        }
        $respond = [
            'status' => 401,
            'message' => 'Kpi not found',
            'data' => null
        ];
        return $respond;
    }

    public function addKpi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $respond = [
                'status' => 401,
                'message' => $validator->errors()->first(),
                'data' => null,
            ];
            return $respond;
        }

        $kpi = new Kpi();
        $kpi->name = $request->name;
        $kpi->save();
        $respond = [
            'status' => 200,
            'message' => 'Kpi added successfully',
            'data' => $kpi
        ];
        return $respond;
    }
    public function deleteKpi($id)
    {
        $kpi = Kpi::find($id);
        $kpis = Kpi::all();
        if (isset($kpi)) {
            $kpi->delete();
            $respond = [
                'status' => 200,
                'message' => 'Kpi deleted successfully',
                'data' => $kpis
            ];
            return $respond;
        }
        $respond = [
            'status' => 401,
            'message' => 'Kpi not found',
            'data' => $kpis
        ];
        return $respond;
    }
    public function updateKpi(Request $request, $id)
    {
        $kpi = Kpi::find($id);
        if (isset($kpi)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                $respond = [
                    'status' => 401,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ];
                return $respond;
            }

            $kpi->name = $request->name;

            $kpi->save();

            $respond = [
                'status' => 200,
                'message' => 'Kpi updated successfully',
                'data' => $kpi
            ];
            return $respond;
        } else {
            $respond = [
                'status' => 401,
                'message' => 'Kpi not found',
                'data' => $kpi
            ];
            return $respond;
        }
    }
}
