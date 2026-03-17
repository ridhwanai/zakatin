<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ZakatRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'people_count',
        'method',
        'rice_kg',
        'fitrah_money',
        'wajib_money',
        'infaq_money',
        'mal_money',
    ];

    protected function casts(): array
    {
        return [
            'people_count' => 'integer',
            'rice_kg' => 'decimal:2',
            'fitrah_money' => 'decimal:2',
            'wajib_money' => 'decimal:2',
            'infaq_money' => 'decimal:2',
            'mal_money' => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
