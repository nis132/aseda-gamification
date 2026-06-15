<?php
// app/Models/Badge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badge';

    protected $fillable = [
        'nama_badge', 'deskripsi', 'icon',
        'poin_minimal', 'level_required',
        'ada_sertifikat', 'tipe_syarat',
    ];

    protected $casts = [
        'ada_sertifikat' => 'boolean',
    ];

    public function siswaBadges()
    {
        return $this->hasMany(SiswaBadge::class);
    }

    /**
     * Label warna per badge berdasarkan level_required.
     */
    public function styleConfig(): array
    {
        return match($this->level_required) {
            2 => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => 'fa-seedling',  'label' => 'Level 2'],
            3 => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'fa-compass',   'label' => 'Level 3'],
            4 => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'fa-dragon',    'label' => 'Level 4'],
            5 => ['bg' => '#fce7f3', 'text' => '#9d174d', 'icon' => 'fa-crown',     'label' => 'Level 5'],
            default => ['bg' => '#f1f5f9', 'text' => '#64748b', 'icon' => 'fa-map-marked-alt', 'label' => 'Spesial'],
        };
    }
}