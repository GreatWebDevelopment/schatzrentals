<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    public const STATUSES = ['new', 'interviewing', 'hired', 'rejected'];

    public const SCORE_CRITERIA = [
        'experience' => 'Move-out / turnover experience',
        'reliability' => 'Turnaround speed & backup coverage',
        'protection' => 'Insurance & invoicing',
        'pricing' => 'Price for a standard unit',
        'quality' => 'Re-clean guarantee & photo proof',
        'references' => 'References check out',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'references' => 'array',
            'scores' => 'array',
            'has_turnover_experience' => 'boolean',
            'has_backup' => 'boolean',
            'weekend_availability' => 'boolean',
            'is_insured' => 'boolean',
            'is_bonded' => 'boolean',
            'provides_invoices' => 'boolean',
            'reclean_guarantee' => 'boolean',
            'sends_photos' => 'boolean',
            'price_1br' => 'decimal:2',
            'price_2br' => 'decimal:2',
            'price_3br' => 'decimal:2',
            'interview_at' => 'datetime',
        ];
    }
}
