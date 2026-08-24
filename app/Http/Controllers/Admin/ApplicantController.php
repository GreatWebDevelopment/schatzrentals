<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ApplicantController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status');

        $applicants = Applicant::query()
            ->when($status && $status !== 'all', fn ($q) => $q->where('status', $status))
            ->orderByRaw('score_total is null')
            ->orderByDesc('score_total')
            ->orderByDesc('created_at')
            ->get();

        $counts = Applicant::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return Inertia::render('applicants/Index', [
            'applicants' => $applicants,
            'counts' => [
                'all' => $counts->sum(),
                'new' => $counts->get('new', 0),
                'interviewing' => $counts->get('interviewing', 0),
                'hired' => $counts->get('hired', 0),
                'rejected' => $counts->get('rejected', 0),
            ],
            'activeStatus' => $status ?: 'all',
        ]);
    }

    public function show(Applicant $applicant): Response
    {
        return Inertia::render('applicants/Show', [
            'applicant' => $applicant,
            'criteria' => Applicant::SCORE_CRITERIA,
        ]);
    }

    public function update(Request $request, Applicant $applicant)
    {
        $data = $request->validate([
            'status' => ['sometimes', Rule::in(Applicant::STATUSES)],
            'admin_notes' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'interview_at' => ['sometimes', 'nullable', 'date'],
            'scores' => ['sometimes', 'nullable', 'array'],
            'scores.*' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        if (array_key_exists('scores', $data)) {
            $scores = collect($data['scores'] ?? [])
                ->only(array_keys(Applicant::SCORE_CRITERIA))
                ->filter(fn ($v) => $v !== null);
            $data['scores'] = $scores->all();
            $data['score_total'] = $scores->isEmpty() ? null : $scores->sum();
        }

        $applicant->update($data);

        return back()->with('success', 'Applicant updated.');
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();

        return to_route('applicants.index')->with('success', 'Applicant deleted.');
    }
}
