<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ZakatCalculatorController extends Controller
{
    public function zakatCalculator(Request $request): View
    {
        /** @var EloquentCollection<int, Project>|Collection<int, Project> $projects */
        $projects = collect();
        $projectPayload = [];
        $selectedProjectId = null;

        if (Auth::check()) {
            $projects = Auth::user()
                ->projects()
                ->latest()
                ->get();

            $projectPayload = $projects
                ->map(function (Project $project): array {
                    return [
                        'id' => (int) $project->id,
                        'title' => $project->title,
                        'hijri_year' => $project->hijri_year,
                        'status' => $project->status,
                        'rice_rate_per_person' => $project->rice_rate_per_person !== null
                            ? (float) $project->rice_rate_per_person
                            : null,
                        'money_rate_per_person' => $project->money_rate_per_person !== null
                            ? (float) $project->money_rate_per_person
                            : null,
                        'summary' => $project->summary(),
                    ];
                })
                ->values()
                ->all();

            $requestedProjectId = (int) $request->integer('project');
            $projectIds = collect($projectPayload)->pluck('id');

            if ($requestedProjectId > 0 && $projectIds->contains($requestedProjectId)) {
                $selectedProjectId = $requestedProjectId;
            } else {
                $selectedProjectId = $projectPayload[0]['id'] ?? null;
            }
        }

        return view('kalkulator-zakat.show', [
            'page' => [
                'title' => 'Kalkulator Zakat',
                'description' => 'Simulasi pembagian zakat berbasis total dari project yang dipilih.',
                'view' => 'kalkulator-zakat.pages.zakat',
                'bodyClass' => '',
                'styles' => [],
                'externalStyles' => [],
                'scripts' => [
                    'assets/kalkulator-zakat/zakat.js',
                ],
            ],
            'isAuthenticated' => Auth::check(),
            'projectPayload' => $projectPayload,
            'selectedProjectId' => $selectedProjectId,
        ]);
    }
}
