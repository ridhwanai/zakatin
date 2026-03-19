<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ZakatRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZakatRecordController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->ensureProjectOwnership($project);
        $validated = $this->validateRecordRequest($request, $project);
        $resolvedAmounts = $this->resolveAmounts($project, $validated);

        ZakatRecord::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'people_count' => (int) $validated['people_count'],
            'method' => $validated['method'],
            'rice_kg' => $resolvedAmounts['rice_kg'],
            'fitrah_money' => $resolvedAmounts['fitrah_money'],
            'wajib_money' => $validated['wajib_money'] ?? 0,
            // Infaq now follows wajib money input.
            'infaq_money' => $validated['wajib_money'] ?? 0,
            'mal_money' => $validated['mal_money'] ?? 0,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Data zakat berhasil ditambahkan.');
    }

    public function update(Request $request, Project $project, ZakatRecord $record): RedirectResponse
    {
        $this->ensureProjectOwnership($project);
        $this->ensureRecordBelongsToProject($project, $record);

        $validated = $this->validateRecordRequest($request, $project);
        $resolvedAmounts = $this->resolveAmounts($project, $validated);

        $record->update([
            'name' => $validated['name'],
            'people_count' => (int) $validated['people_count'],
            'method' => $validated['method'],
            'rice_kg' => $resolvedAmounts['rice_kg'],
            'fitrah_money' => $resolvedAmounts['fitrah_money'],
            'wajib_money' => $validated['wajib_money'] ?? 0,
            'infaq_money' => $validated['wajib_money'] ?? 0,
            'mal_money' => $validated['mal_money'] ?? 0,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Data zakat berhasil diperbarui.');
    }

    public function destroy(Project $project, ZakatRecord $record): RedirectResponse
    {
        $this->ensureProjectOwnership($project);
        $this->ensureRecordBelongsToProject($project, $record);

        $record->delete();

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Data zakat berhasil dihapus.');
    }

    private function ensureProjectOwnership(Project $project): void
    {
        abort_unless($project->user_id === Auth::id(), 403);
    }

    private function ensureRecordBelongsToProject(Project $project, ZakatRecord $record): void
    {
        abort_unless($record->project_id === $project->id, 404);
    }

    /**
     * @return array{
     *     name: string,
     *     people_count: int|string,
     *     method: string,
     *     rice_kg: float|int|string|null,
     *     fitrah_money: float|int|string|null,
     *     wajib_money: float|int|string|null,
     *     mal_money: float|int|string|null
     * }
     */
    private function validateRecordRequest(Request $request, Project $project): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'people_count' => ['required', 'integer', 'min:1', 'max:1000'],
            'method' => ['required', 'in:rice,money,custom'],
            'rice_kg' => ['nullable', 'numeric', 'min:0'],
            'fitrah_money' => ['nullable', 'numeric', 'min:0'],
            'wajib_money' => ['nullable', 'numeric', 'min:0'],
            'mal_money' => ['nullable', 'numeric', 'min:0'],
        ];

        if ($project->rice_rate_per_person === null) {
            $rules['rice_kg'][] = 'required_if:method,rice';
        }

        if ($project->money_rate_per_person === null) {
            $rules['fitrah_money'][] = 'required_if:method,money';
        }

        return $request->validate($rules, [
            'name.required' => 'Nama pembayar wajib diisi.',
            'people_count.required' => 'Jumlah orang wajib diisi.',
            'people_count.min' => 'Jumlah orang minimal 1.',
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'rice_kg.required_if' => 'Total beras wajib diisi saat metode Beras dipilih.',
            'fitrah_money.required_if' => 'Zakat fitrah uang wajib diisi saat metode Tunai dipilih.',
        ]);
    }

    /**
     * @param  array{
     *     people_count: int|string,
     *     method: string,
     *     rice_kg: float|int|string|null,
     *     fitrah_money: float|int|string|null
     * }  $validated
     * @return array{rice_kg: float|null, fitrah_money: float|null}
     */
    private function resolveAmounts(Project $project, array $validated): array
    {
        $peopleCount = (int) $validated['people_count'];
        $riceKg = null;
        $fitrahMoney = null;

        if ($validated['method'] === 'rice') {
            $riceKg = $project->rice_rate_per_person !== null
                ? round($peopleCount * (float) $project->rice_rate_per_person, 2)
                : (float) $validated['rice_kg'];
        }

        if ($validated['method'] === 'money') {
            $fitrahMoney = $project->money_rate_per_person !== null
                ? round($peopleCount * (float) $project->money_rate_per_person, 2)
                : (float) $validated['fitrah_money'];
        }

        if ($validated['method'] === 'custom') {
            $riceKg = $validated['rice_kg'] !== null ? (float) $validated['rice_kg'] : null;
            $fitrahMoney = $validated['fitrah_money'] !== null ? (float) $validated['fitrah_money'] : null;
        }

        return [
            'rice_kg' => $riceKg,
            'fitrah_money' => $fitrahMoney,
        ];
    }
}
