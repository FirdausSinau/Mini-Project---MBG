<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Models\Distribution;
use App\Models\School;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $schools = School::withCount('distributions')
            ->orderBy('nama')
            ->get();

        $distributions = Distribution::with('school')
            ->orderByDesc('tanggal')
            ->get();

        return view('master-data.index', compact('schools', 'distributions'));
    }

    public function storeSchool(StoreSchoolRequest $request): RedirectResponse
    {
        School::create($request->validated());

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function storeDistribution(StoreDistributionRequest $request): RedirectResponse
    {
        Distribution::create($request->validated());

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Distribusi berhasil ditambahkan.');
    }
}
