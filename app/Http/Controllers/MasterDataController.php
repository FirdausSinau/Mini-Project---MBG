<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateDistributionRequest;
use App\Http\Requests\UpdateSchoolRequest;
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

    public function editSchool(School $school): View
    {
        return view('master-data.schools.edit', compact('school'));
    }

    public function updateSchool(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $school->update($request->validated());

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroySchool(School $school): RedirectResponse
    {
        if ($school->distributions()->exists()) {
            return redirect()
                ->route('master-data.index')
                ->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki distribusi.');
        }

        $school->delete();

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    public function editDistribution(Distribution $distribution): View
    {
        $schools = School::orderBy('nama')->get();

        return view('master-data.distributions.edit', compact('distribution', 'schools'));
    }

    public function updateDistribution(UpdateDistributionRequest $request, Distribution $distribution): RedirectResponse
    {
        $distribution->update($request->validated());

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Distribusi berhasil diperbarui.');
    }

    public function destroyDistribution(Distribution $distribution): RedirectResponse
    {
        // Saat tabel reports sudah ada, foreign key reports.distribution_id
        // akan menolak penghapusan distribusi yang masih dipakai laporan.
        $distribution->delete();

        return redirect()
            ->route('master-data.index')
            ->with('success', 'Distribusi berhasil dihapus.');
    }
}
