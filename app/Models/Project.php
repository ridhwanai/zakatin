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
     *     total_list_count_rice:int,
     *     total_list_count_money:int,
     *     total_list_count_custom:int,
     *     total_list_count_mal:int,
     *     total_people:int,
     *     total_people_rice:int,
     *     total_people_money:int,
     *     total_people_custom:int,
     *     total_people_mal:int,
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
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'rice' THEN 1 ELSE 0 END), 0) as total_list_count_rice")
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'money' THEN 1 ELSE 0 END), 0) as total_list_count_money")
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'custom' THEN 1 ELSE 0 END), 0) as total_list_count_custom")
            ->selectRaw('COALESCE(SUM(CASE WHEN mal_money > 0 THEN 1 ELSE 0 END), 0) as total_list_count_mal')
            ->selectRaw('COALESCE(SUM(people_count), 0) as total_people')
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'rice' THEN people_count ELSE 0 END), 0) as total_people_rice")
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'money' THEN people_count ELSE 0 END), 0) as total_people_money")
            ->selectRaw("COALESCE(SUM(CASE WHEN method = 'custom' THEN people_count ELSE 0 END), 0) as total_people_custom")
            ->selectRaw('COALESCE(SUM(CASE WHEN mal_money > 0 THEN people_count ELSE 0 END), 0) as total_people_mal')
            ->selectRaw('COALESCE(SUM(rice_kg), 0) as total_rice_kg')
            ->selectRaw('COALESCE(SUM(fitrah_money), 0) as total_fitrah_money')
            ->selectRaw('COALESCE(SUM(wajib_money), 0) as total_wajib_money')
            ->selectRaw('COALESCE(SUM(mal_money), 0) as total_mal_money')
            ->first();

        return [
            'total_list_count' => (int) $summary->total_list_count,
            'total_list_count_rice' => (int) $summary->total_list_count_rice,
            'total_list_count_money' => (int) $summary->total_list_count_money,
            'total_list_count_custom' => (int) $summary->total_list_count_custom,
            'total_list_count_mal' => (int) $summary->total_list_count_mal,
            'total_people' => (int) $summary->total_people,
            'total_people_rice' => (int) $summary->total_people_rice,
            'total_people_money' => (int) $summary->total_people_money,
            'total_people_custom' => (int) $summary->total_people_custom,
            'total_people_mal' => (int) $summary->total_people_mal,
            'total_rice_kg' => (float) $summary->total_rice_kg,
            'total_fitrah_money' => (float) $summary->total_fitrah_money,
            'total_wajib_money' => (float) $summary->total_wajib_money,
            'total_mal_money' => (float) $summary->total_mal_money,
        ];
    }
}
