<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\HijriCalendar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        /** @var EloquentCollection<int, Project>|Collection<int, Project> $projects */
        $projects = collect();

        if (Auth::check()) {
            $projects = Auth::user()
                ->projects()
                ->withCount('zakatRecords')
                ->latest()
                ->get();
        }

        return view('projects.index', [
            'projects' => $projects,
            'currentHijriYear' => HijriCalendar::currentYear(),
            'isAuthenticated' => Auth::check(),
            'canManageProjects' => Auth::check() && Auth::user()->hasVerifiedEmail(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $hijriYear = HijriCalendar::currentYear();
        $title = sprintf('Zakat %s H', $hijriYear);

        $project = Project::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'hijri_year' => $hijriYear,
            ],
            [
                'title' => $title,
                'status' => 'active',
            ]
        );

        $redirect = redirect()->route('projects.show', $project);

        if ($project->wasRecentlyCreated) {
            return $redirect->with('success', 'Project baru berhasil dibuat.');
        }

        return $redirect->with('info', 'Project tahun ini sudah ada, diarahkan ke detail project.');
    }

    public function show(Project $project): View
    {
        $this->ensureOwnership($project);

        $project->load(['zakatRecords' => function ($query): void {
            $query->latest();
        }]);

        return view('projects.show', [
            'project' => $project,
            'summary' => $project->summary(),
        ]);
    }

    public function updateRates(Request $request, Project $project): RedirectResponse
    {
        $this->ensureOwnership($project);

        $validated = $request->validate([
            'rice_rate_per_person' => ['nullable', 'numeric', 'gt:0'],
            'money_rate_per_person' => ['nullable', 'numeric', 'gt:0'],
        ], [
            'rice_rate_per_person.gt' => 'Nominal beras per orang harus lebih dari 0.',
            'money_rate_per_person.gt' => 'Nominal uang per orang harus lebih dari 0.',
        ]);

        $project->update([
            'rice_rate_per_person' => $validated['rice_rate_per_person'] ?? null,
            'money_rate_per_person' => $validated['money_rate_per_person'] ?? null,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'Set nominal per orang berhasil disimpan.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->ensureOwnership($project);
        $project->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Project berhasil dihapus.');
    }

    public function pdf(Project $project)
    {
        $this->ensureOwnership($project);

        $project->load(['zakatRecords' => function ($query): void {
            $query->oldest();
        }]);

        $summary = $project->summary();

        $pdf = Pdf::loadView('projects.pdf', [
            'project' => $project,
            'summary' => $summary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download(Str::slug($project->title).'-rekap-zakat.pdf');
    }

    private function ensureOwnership(Project $project): void
    {
        abort_unless($project->user_id === Auth::id(), 403);
    }
}
