<?php

namespace App\Http\Controllers;

use App\Models\FuelRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;


class FuelRecordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fuel_amount' => 'required|numeric|min:0.1',
            'distance' => 'required|numeric|min:0.1',
            'date' => 'required|date'
        ]);

        Log::debug('保存内容', [
            'fuel' => $request->fuel_amount,
            'distance' => $request->distance,
            // 'user' => auth()->id(),
            'user' => 1,
            'date' => $request->date
        ]);

        $efficiency = $request->distance / $request->fuel_amount;

        FuelRecord::create([
            'vehicle_id' => 1, // 仮で固定。後で切り替え可
            'user_id' => auth()->id(),
            'vehicle_id' => $request->vehicle_id,
            'fuel_amount' => $request->fuel_amount,
            'distance' => $request->distance,
            'fuel_efficiency' => round($efficiency, 2),
            'created_at' => $request->date,
            'updated_at' => now()
        ]);

        return redirect()->route('fuel.history')->with('message', '燃費データを登録しました！');
    }

    public function history(Request $request)
    {
        $vehicle_id = $request->query('vehicle_id');

        // 該当車両のデータだけ抽出
        $records = FuelRecord::where('vehicle_id', $vehicle_id)
            ->orderBy('created_at')->get();

        // 車種名の取得
        $vehicle_name = Vehicle::find($vehicle_id)?->name ?? '指定なし';

        // グラフ用ラベルとデータ
        $labels = $records->pluck('created_at')->map(fn($d) => $d->format('Y-m-d'));
        $efficiencies = $records->pluck('fuel_efficiency');

        return view('fuel_history', compact('records', 'labels', 'efficiencies', 'vehicle_name'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('id', 'asc')->get();
        return view('fuel_entry', compact('vehicles'));

    }

    public function export(): StreamedResponse
    {
        $records = FuelRecord::orderBy('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fuel_history.csv"',
        ];

        return response()->stream(function () use ($records) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['日付', '走行距離(km)', '給油量(L)', '燃費(km/L)']);

            foreach ($records as $r) {
                fputcsv($stream, [
                    $r->created_at->format('Y-m-d'),
                    $r->distance,
                    $r->fuel_amount,
                    number_format($r->fuel_efficiency, 2),
                ]);
            }

            fclose($stream);
        }, 200, $headers);
    }
}
