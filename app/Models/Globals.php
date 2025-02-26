<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Globals extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'id_no_lp',
        'tanggal_lp',
        'hasil_tangkapan',
        'jenis_kasus',
        'kronologi',
        'tersangka_nama',
        'tersangka_nik',
        'tersangka_warganegara',
        'tersangka_suku',
        'tersangka_jk',
        'tersangka_tempat_tgl_lahir',
        'tersangka_umur',
        'tersangka_agama',
        'tersangka_pekerjaan',
        'tersangka_alamat',
        'korban',
        'saksi_nama',
        'saksi_nik',
        'saksi_warganegara',
        'saksi_suku',
        'saksi_jk',
        'saksi_tempat_tgl_lahir',
        'saksi_umur',
        'saksi_agama',
        'saksi_pekerjaan',
        'saksi_alamat',
        'melanggar_pasal',
        'barang_bukti',
        'kerugian',
        'ba_serah_terima',
        'keterangan',
        'created_by',
        'updated_by'
    ];
}
