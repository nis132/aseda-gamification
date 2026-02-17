<?php
// app/Models/Badge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badge';
    
    protected $fillable = [
        'nama_badge',
        'deskripsi',
        'icon',
        'poin_min'
    ];

    public function siswaBadges()
    {
        return $this->hasMany(SiswaBadge::class);
    }
}
