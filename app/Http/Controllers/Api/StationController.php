<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Billing\AddTimeRequest;
use App\Http\Requests\Api\Billing\StartStationRequest;
use App\Models\Station;
use App\Services\Billing\StationService;

class StationController extends Controller
{
    protected $stationService;

    public function __construct(StationService $stationService)
    {
        $this->stationService = $stationService;
    }

    public function index()
    {
        return response()->json($this->stationService->list());
    }

    public function start(StartStationRequest $request, Station $station)
    {
        return response()->json(
            $this->stationService->start(
                $station, 
                $request->input('customer_name'), 
                $request->user()->id,
                (int) $request->input('package_minutes', 0)
            )
        );
    }

    public function pause(Station $station)
    {
        return response()->json($this->stationService->pause($station));
    }

    public function stop(Station $station)
    {
        return response()->json($this->stationService->stop($station));
    }

    public function addTime(AddTimeRequest $request, Station $station)
    {
        return response()->json(
            $this->stationService->addTime($station, (int) $request->input('minutes'))
        );
    }
}
