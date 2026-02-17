<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';  // ✅ Sesuai tabel
    protected $fillable = ['nama_mapel'];  // ✅ Sesuai kolom

    public function scopeSearch($query, $search)
    {
        return $query->where('nama_mapel', 'like', "%{$search}%");
    }

    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'mapel_id');
    }
    public function guru()
    {
        return $this->belongsToMany(User::class, 'guru_mapel', 'mapel_id', 'guru_id');
    }
}
