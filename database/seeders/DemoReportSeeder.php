<?php

namespace Database\Seeders;

use App\Enums\ReferralParty;
use App\Enums\ReportStatus;
use App\Models\Distribution;
use App\Models\Report;
use App\Models\ReportStatusLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DemoReportSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed laporan demo untuk semua status dan skenario.
     */
    public function run(): void
    {
        $giorno = User::where('username', 'Giorno')->firstOrFail();
        $jotaro = User::where('username', 'Jotaro')->firstOrFail();
        $dramok = User::where('username', 'Dramok')->firstOrFail();
        $perrell = User::where('username', 'Perrell')->firstOrFail();

        $this->clearDemoReports();

        $evidencePath = 'evidence/demo/bukti-demo.png';

        Storage::disk('local')->put(
            $evidencePath,
            file_get_contents(database_path('seeders/fixtures/bukti-demo.png'))
        );

        $reports = [
            [
                'user' => $giorno,
                'distribution_id' => 1,
                'status' => ReportStatus::Baru,
                'description' => 'Laporan demo: siswa melihat kemasan makanan penyok saat distribusi.',
                'location_detail' => 'Kantin sekolah',
                'evidence' => true,
            ],
            [
                'user' => $jotaro,
                'distribution_id' => 1,
                'status' => ReportStatus::Baru,
                'description' => 'Laporan demo: beberapa siswa mengeluh makanan terasa asam.',
                'location_detail' => 'Kelas 7A',
            ],
            [
                'user' => $giorno,
                'distribution_id' => 4,
                'status' => ReportStatus::Ditinjau,
                'description' => 'Laporan demo: nasi berbau tidak sedap saat diterima.',
                'location_detail' => 'Ruang guru',
                'review_note' => 'Laporan sudah diperiksa. Tidak perlu diteruskan.',
            ],
            [
                'user' => $jotaro,
                'distribution_id' => 5,
                'status' => ReportStatus::PerluPerbaikan,
                'description' => 'Laporan demo: ada siswa yang merasa mual setelah makan.',
                'location_detail' => 'Kantin sekolah',
                'review_note' => 'Lokasi kejadian belum jelas. Mohon lengkapi.',
            ],
            [
                'user' => $giorno,
                'distribution_id' => 7,
                'status' => ReportStatus::PerluDiteruskan,
                'description' => 'Laporan demo: ditemukan benda asing pada lauk.',
                'location_detail' => 'Kantin sekolah',
                'review_note' => 'Perlu diteruskan ke fasilitas kesehatan terdekat.',
            ],
            [
                'user' => $jotaro,
                'distribution_id' => 8,
                'status' => ReportStatus::Diteruskan,
                'description' => 'Laporan demo: siswa mengalami gatal setelah makan.',
                'location_detail' => 'Kelas 8B',
                'review_note' => 'Perlu diteruskan ke puskesmas.',
                'referral' => [
                    'party_type' => ReferralParty::Faskes,
                    'party_name' => 'Puskesmas Depok',
                    'note' => 'Rujuk untuk pemeriksaan lanjutan.',
                ],
            ],
            [
                'user' => $giorno,
                'distribution_id' => 9,
                'status' => ReportStatus::Selesai,
                'description' => 'Laporan demo: makanan tidak sesuai menu yang tercantum.',
                'location_detail' => 'Kantin sekolah',
                'review_note' => 'Laporan sudah ditinjau. Tidak perlu diteruskan.',
                'closure_note' => 'Pemantauan selesai, tidak ada tindak lanjut.',
                'closed_after_review' => true,
            ],
            [
                'user' => $jotaro,
                'distribution_id' => 2,
                'status' => ReportStatus::Selesai,
                'description' => 'Laporan demo: siswa muntah setelah konsumsi makanan.',
                'location_detail' => 'UKS',
                'review_note' => 'Perlu diteruskan ke sekolah.',
                'referral' => [
                    'party_type' => ReferralParty::Sekolah,
                    'party_name' => 'SMP 2 Mlati Sleman',
                    'note' => 'Koordinasi dengan sekolah selesai.',
                ],
                'closure_note' => 'Penerusan sudah dikonfirmasi.',
                'closed_after_review' => false,
            ],
        ];

        foreach ($reports as $data) {
            $distribution = Distribution::findOrFail($data['distribution_id']);
            $incidentAt = $distribution->tanggal->copy()->addHours(10);
            $reviewedAt = $incidentAt->copy()->addHour();
            $referredAt = $reviewedAt->copy()->addHour();
            $closedAt = $referredAt->copy()->addHour();

            $report = Report::create([
                'user_id' => $data['user']->id,
                'distribution_id' => $distribution->id,
                'incident_at' => $incidentAt,
                'description' => $data['description'],
                'location_detail' => $data['location_detail'],
                'status' => $data['status'],
                'evidence_path' => ! empty($data['evidence']) ? $evidencePath : null,
                'evidence_original_name' => ! empty($data['evidence']) ? 'bukti-demo.png' : null,
                'review_note' => $data['review_note'] ?? null,
                'reviewed_by' => isset($data['review_note']) ? $dramok->id : null,
                'reviewed_at' => isset($data['review_note']) ? $reviewedAt : null,
                'referral_party_type' => $data['referral']['party_type'] ?? null,
                'referral_party_name' => $data['referral']['party_name'] ?? null,
                'referral_note' => $data['referral']['note'] ?? null,
                'referred_by' => isset($data['referral']) ? $perrell->id : null,
                'referred_at' => isset($data['referral']) ? $referredAt : null,
                'closure_note' => $data['closure_note'] ?? null,
                'closed_by' => isset($data['closure_note']) ? $perrell->id : null,
                'closed_at' => isset($data['closure_note']) ? $closedAt : null,
            ]);

            $this->seedStatusLogs(
                $report,
                $data,
                $dramok,
                $perrell,
                $reviewedAt,
                $referredAt,
                $closedAt,
            );
        }
    }

    /**
     * Hapus laporan demo lama beserta bukti dan log-nya.
     */
    private function clearDemoReports(): void
    {
        $demoReports = Report::where('description', 'like', 'Laporan demo:%')->get();
        $ids = $demoReports->pluck('id');

        foreach ($demoReports as $report) {
            if ($report->evidence_path) {
                Storage::disk('local')->delete($report->evidence_path);
            }
        }

        Storage::disk('local')->deleteDirectory('evidence/demo');

        if ($ids->isNotEmpty()) {
            ReportStatusLog::whereIn('report_id', $ids)->delete();
            Report::whereIn('id', $ids)->delete();
        }
    }

    /**
     * Buat riwayat status sesuai skenario laporan.
     */
    private function seedStatusLogs(
        Report $report,
        array $data,
        User $reviewer,
        User $coordinator,
        Carbon $reviewedAt,
        Carbon $referredAt,
        Carbon $closedAt,
    ): void {
        $status = $data['status'];

        if ($status === ReportStatus::Baru) {
            return;
        }

        $firstStatus = match ($status) {
            ReportStatus::Diteruskan => ReportStatus::PerluDiteruskan,
            ReportStatus::Selesai => $data['closed_after_review']
                ? ReportStatus::Ditinjau
                : ReportStatus::PerluDiteruskan,
            default => $status,
        };

        $this->addLog($report, ReportStatus::Baru, $firstStatus, $reviewer, $reviewedAt);

        if (
            $status === ReportStatus::Diteruskan
            || ($status === ReportStatus::Selesai && ! $data['closed_after_review'])
        ) {
            $this->addLog(
                $report,
                ReportStatus::PerluDiteruskan,
                ReportStatus::Diteruskan,
                $coordinator,
                $referredAt,
            );
        }

        if ($status === ReportStatus::Selesai) {
            $from = $data['closed_after_review']
                ? ReportStatus::Ditinjau
                : ReportStatus::Diteruskan;

            $this->addLog($report, $from, ReportStatus::Selesai, $coordinator, $closedAt);
        }
    }

    /**
     * Tambah satu baris log status dengan waktu dan aktor tertentu.
     */
    private function addLog(
        Report $report,
        ReportStatus $from,
        ReportStatus $to,
        User $actor,
        Carbon $createdAt,
    ): void {
        $log = new ReportStatusLog([
            'report_id' => $report->id,
            'from_status' => $from,
            'to_status' => $to,
            'actor_id' => $actor->id,
        ]);

        $log->created_at = $createdAt;
        $log->save();
    }
}
