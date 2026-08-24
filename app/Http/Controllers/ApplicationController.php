<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicantRequest;
use App\Models\Applicant;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('apply/Form');
    }

    public function store(StoreApplicantRequest $request)
    {
        $data = $request->validated();

        // Drop empty reference rows before saving.
        $data['references'] = collect($data['references'] ?? [])
            ->filter(fn ($ref) => filled($ref['name'] ?? null) || filled($ref['phone'] ?? null))
            ->values()
            ->all();

        Applicant::create($data);

        return to_route('apply.thanks');
    }

    public function thanks(): Response
    {
        return Inertia::render('apply/ThankYou');
    }
}
