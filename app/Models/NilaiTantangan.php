<?php
// app/Models/NilaiTantangan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTantangan extends Model
{
    protected $table = 'nilai_tantangan';
    
    protected $fillable = [
        'siswa_id',
        'tantangan_id',
        'total_nilai',
        'poin_didapat',
        'waktu_submit'
    ];

    protected $casts = [
        'total_nilai' => 'decimal:2',
        'poin_didapat' => 'integer',
        'waktu_submit' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function tantangan()
    {
        return $this->belongsTo(Tantangan::class);
    }
}
