<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\School;
use Illuminate\Http\JsonResponse;

class DistributionLookupController extends Controller
{
    public function index(School $school): JsonResponse
    {
        $distributions = $school->distributions()
            ->orderByDesc('tanggal')
            ->get()
            ->map(fn (Distribution $distribution) => [
                'id' => $distribution->id,
                'label' => $distribution->tanggal->format('d M Y')
                    . ' - '
                    . ($distribution->menu ?? 'Menu belum dicatat'),
            ]);

        return response()->json($distributions);
    }
}
