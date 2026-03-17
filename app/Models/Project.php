<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'hijri_year',
        'status',
        'rice_rate_per_person',
        'money_rate_per_person',
    ];

    protected function casts(): array
    {
        return [
            'rice_rate_per_person' => 'decimal:2',
            'money_rate_per_person' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function zakatRecords(): HasMany
    {
        return $this->hasMany(ZakatRecord::class);
    }

    /**
     * @return array{
     *     total_list_count:int,
     *     total_people:int,
     *     total_rice_kg:float,
     *     total_fitrah_money:float,
     *     total_wajib_money:float,
     *     total_mal_money:float
     * }
     */
    public function summary(): array
    {
        $summary = $this->zakatRecords()
            ->selectRaw('COALESCE(COUNT(*), 0) as total_list_count')
            ->selectRaw('COALESCE(SUM(people_count), 0) as total_people')
            ->selectRaw('COALESCE(SUM(rice_kg), 0) as total_rice_kg')
            ->selectRaw('COALESCE(SUM(fitrah_money), 0) as total_fitrah_money')
            ->selectRaw('COALESCE(SUM(wajib_money), 0) as total_wajib_money')
            ->selectRaw('COALESCE(SUM(mal_money), 0) as total_mal_money')
            ->first();

        return [
            'total_list_count' => (int) $summary->total_list_count,
            'total_people' => (int) $summary->total_people,
            'total_rice_kg' => (float) $summary->total_rice_kg,
            'total_fitrah_money' => (float) $summary->total_fitrah_money,
            'total_wajib_money' => (float) $summary->total_wajib_money,
            'total_mal_money' => (float) $summary->total_mal_money,
        ];
    }
}
