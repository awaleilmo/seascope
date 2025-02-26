<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Gakkum
 *
 * @property int $id
 * @property string $no_lp
 * @property string $tanggal_lp
 * @property string|null $hasil_tangkapan
 * @property string|null $jenis_kasus
 * @property string|null $kronologis
 * @property string|null $tersangka_nama
 * @property string|null $tersangka_nik
 * @property string|null $tersangka_warganegara
 * @property string|null $tersangka_suku
 * @property string|null $tersangka_jk
 * @property string|null $tersangka_tempat_tgl_lahir
 * @property string|null $tersangka_umur
 * @property string|null $tersangka_agama
 * @property string|null $tersangka_pekerjaan
 * @property string|null $tersangka_alamat
 * @property string|null $korban
 * @property string|null $saksi_nama
 * @property string|null $saksi_nik
 * @property string|null $saksi_warganegara
 * @property string|null $saksi_suku
 * @property string|null $saksi_jk
 * @property string|null $saksi_tempat_tgl_lahir
 * @property string|null $saksi_umur
 * @property string|null $saksi_agama
 * @property string|null $saksi_pekerjaan
 * @property string|null $saksi_alamat
 * @property string|null $melanggar_pasal
 * @property string|null $barang_bukti
 * @property int|null $kerugian
 * @property string|null $penyidik
 * @property string|null $penanganan_perkara
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $barang_bukti_short
 * @property-read mixed $kerugian_formated
 * @property-read mixed $kronologis_short
 * @property-read mixed $melanggar_pasal_short
 * @property-read mixed $saksi_short
 * @property-read mixed $tanggal_fotmated
 * @method static \Database\Factories\GakkumFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereBarangBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereHasilTangkapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereJenisKasus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereKerugian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereKorban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereKronologis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereMelanggarPasal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereNoLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum wherePenangananPerkara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum wherePenyidik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereSaksiWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTanggalLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereTersangkaWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gakkum whereUpdatedAt($value)
 */
	class Gakkum extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Globals
 *
 * @property int $id
 * @property string $id_no_lp
 * @property string $tanggal_lp
 * @property string|null $hasil_tangkapan
 * @property string|null $jenis_kasus
 * @property string|null $kronologi
 * @property string|null $tersangka_nama
 * @property string|null $tersangka_nik
 * @property string|null $tersangka_warganegara
 * @property string|null $tersangka_suku
 * @property string|null $tersangka_jk
 * @property string|null $tersangka_tempat_tgl_lahir
 * @property string|null $tersangka_umur
 * @property string|null $tersangka_agama
 * @property string|null $tersangka_pekerjaan
 * @property string|null $tersangka_alamat
 * @property string|null $korban
 * @property string|null $saksi_nama
 * @property string|null $saksi_nik
 * @property string|null $saksi_warganegara
 * @property string|null $saksi_suku
 * @property string|null $saksi_jk
 * @property string|null $saksi_tempat_tgl_lahir
 * @property string|null $saksi_umur
 * @property string|null $saksi_agama
 * @property string|null $saksi_pekerjaan
 * @property string|null $saksi_alamat
 * @property string|null $melanggar_pasal
 * @property string|null $barang_bukti
 * @property string|null $kerugian
 * @property string|null $ba_serah_terima
 * @property string|null $keterangan
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Globals newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Globals newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Globals query()
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereBaSerahTerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereBarangBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereHasilTangkapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereIdNoLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereJenisKasus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereKerugian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereKorban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereKronologi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereMelanggarPasal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereSaksiWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTanggalLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereTersangkaWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Globals whereUpdatedBy($value)
 */
	class Globals extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Kia
 *
 * @property int $id
 * @property string $no_lp
 * @property string $tanggal_lp
 * @property string|null $hasil_tangkapan
 * @property string|null $jenis_kasus
 * @property string|null $kronologis
 * @property string|null $tersangka_nama
 * @property string|null $tersangka_nik
 * @property string|null $tersangka_warganegara
 * @property string|null $tersangka_suku
 * @property string|null $tersangka_jk
 * @property string|null $tersangka_tempat_tgl_lahir
 * @property string|null $tersangka_umur
 * @property string|null $tersangka_agama
 * @property string|null $tersangka_pekerjaan
 * @property string|null $tersangka_alamat
 * @property string|null $korban
 * @property string|null $saksi_nama
 * @property string|null $saksi_nik
 * @property string|null $saksi_warganegara
 * @property string|null $saksi_suku
 * @property string|null $saksi_jk
 * @property string|null $saksi_tempat_tgl_lahir
 * @property string|null $saksi_umur
 * @property string|null $saksi_agama
 * @property string|null $saksi_pekerjaan
 * @property string|null $saksi_alamat
 * @property string|null $melanggar_pasal
 * @property string|null $barang_bukti
 * @property int|null $kerugian
 * @property string|null $penyidik
 * @property string|null $penanganan_perkara
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $barang_bukti_short
 * @property-read mixed $kerugian_formated
 * @property-read mixed $kronologis_short
 * @property-read mixed $melanggar_pasal_short
 * @property-read mixed $saksi_short
 * @property-read mixed $tanggal_fotmated
 * @method static \Database\Factories\KiaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Kia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereBarangBukti($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereHasilTangkapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereJenisKasus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereKerugian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereKorban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereKronologis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereMelanggarPasal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereNoLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia wherePenangananPerkara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia wherePenyidik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereSaksiWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTanggalLp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaJk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaTempatTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereTersangkaWarganegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kia whereUpdatedAt($value)
 */
	class Kia extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property int $sub
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TargetPU
 *
 * @property int $id
 * @property int $klinik
 * @property int $perpustakaan
 * @property int $rw
 * @property int $sambang
 * @property int $sapu
 * @property int $tahun
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU query()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereKlinik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU wherePerpustakaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereSambang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereSapu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetPU whereUpdatedAt($value)
 */
	class TargetPU extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property int $role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\foto_klinik
 *
 * @property int $id
 * @property int $report_klinik_id
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik query()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik whereReportKlinikId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_klinik whereUpdatedAt($value)
 */
	class foto_klinik extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\foto_perpustakaan
 *
 * @property int $id
 * @property int $report_perpustakaan_id
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan whereReportPerpustakaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_perpustakaan whereUpdatedAt($value)
 */
	class foto_perpustakaan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\foto_rw
 *
 * @property int $id
 * @property int $report_rw_id
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw query()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw whereReportRwId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_rw whereUpdatedAt($value)
 */
	class foto_rw extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\foto_sambang
 *
 * @property int $id
 * @property int $report_sambang_id
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang query()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang whereReportSambangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sambang whereUpdatedAt($value)
 */
	class foto_sambang extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\foto_sapu
 *
 * @property int $id
 * @property int $report_sapu_id
 * @property string $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu query()
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu whereReportSapuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|foto_sapu whereUpdatedAt($value)
 */
	class foto_sapu extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\giat
 *
 * @property int $id
 * @property int $lokasi_id
 * @property int $sambang
 * @property int $rw
 * @property int $perpustakaan
 * @property int $klinik
 * @property int $sampah
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|giat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|giat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|giat query()
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereKlinik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat wherePerpustakaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereSambang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereSampah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|giat whereUpdatedAt($value)
 */
	class giat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\lokasi
 *
 * @property int $id
 * @property string $name
 * @property string|null $alias
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi query()
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|lokasi whereUpdatedAt($value)
 */
	class lokasi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\personil_sapu
 *
 * @property int $id
 * @property int $report_sapu_id
 * @property string $name
 * @property int $jml
 * @property string $sat
 * @property int $index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu query()
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereReportSapuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|personil_sapu whereUpdatedAt($value)
 */
	class personil_sapu extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\polda
 *
 * @property int $id
 * @property string $name
 * @property string|null $kota
 * @property string|null $alamat
 * @property int $lokasi_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|polda newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|polda newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|polda query()
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereLokasiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|polda whereUpdatedAt($value)
 */
	class polda extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\report_klinik
 *
 * @property int $id
 * @property int $polda_id
 * @property mixed $personil_jml [1,2,3]
 * @property mixed $personil_sat ['sat','sat','sat']
 * @property string|null $lokasi
 * @property int|null $jml_peserta
 * @property string|null $keluhan_peserta
 * @property string|null $obat
 * @property string|null $keterangan
 * @property string|null $foto
 * @property string $tanggal
 * @property string|null $waktu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik query()
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereJmlPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereKeluhanPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereObat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik wherePersonilJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik wherePersonilSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik wherePoldaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_klinik whereWaktu($value)
 */
	class report_klinik extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\report_perpustakaan
 *
 * @property int $id
 * @property int $polda_id
 * @property mixed $personil_jml [1,2,3]
 * @property mixed $personil_sat ['sat','sat','sat']
 * @property string|null $lokasi
 * @property int|null $jml_peserta
 * @property string|null $asal_peserta
 * @property string|null $hasil
 * @property string|null $keterangan
 * @property string|null $foto
 * @property string $tanggal
 * @property string|null $waktu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereAsalPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereHasil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereJmlPeserta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan wherePersonilJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan wherePersonilSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan wherePoldaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_perpustakaan whereWaktu($value)
 */
	class report_perpustakaan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\report_rw
 *
 * @property int $id
 * @property int $polda_id
 * @property mixed $personil_jml [1,2,3,4,5]
 * @property mixed $personil_satu ['sat','sat','sat','sat','sat']
 * @property string|null $sambang
 * @property string|null $pemecahaan
 * @property string|null $informasi
 * @property string|null $penanganan
 * @property string|null $keterangan
 * @property string|null $foto
 * @property string $tanggal
 * @property string|null $waktu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw query()
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereInformasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw wherePemecahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw wherePenanganan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw wherePersonilJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw wherePersonilSatu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw wherePoldaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereSambang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_rw whereWaktu($value)
 */
	class report_rw extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\report_sambang
 *
 * @property int $id
 * @property int $polda_id
 * @property mixed $personil_jml [1,2,3]
 * @property mixed $personil_sat ['pers','pers','pers']
 * @property string|null $lokasi
 * @property string|null $sambang
 * @property string|null $binluh
 * @property string|null $baksos
 * @property string|null $keterangan
 * @property string $tipe binaan, sentuhan, pantauan
 * @property string|null $foto
 * @property string $tanggal
 * @property string|null $waktu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang query()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereBaksos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereBinluh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang wherePersonilJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang wherePersonilSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang wherePoldaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereSambang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sambang whereWaktu($value)
 */
	class report_sambang extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\report_sapu
 *
 * @property int $id
 * @property int $polda_id
 * @property mixed $personil_ket ['Ditpolairud', 'Sarlinmas', 'Relawan Pantas', 'Nelayan','Pelaku Kuliner','FPRB']
 * @property mixed $personil_jml [30,5,15,15,10,5]
 * @property mixed $personil_sat ['Pers','Pers', 'Pers', 'Pers','Pers', 'Pers']
 * @property string|null $lokasi
 * @property float|null $sampah_organik_jml Kg
 * @property string|null $sampah_organik_ket
 * @property float|null $sampah_anorganik_jml Kg
 * @property string|null $sampah_anorganik_ket
 * @property string|null $keterangan
 * @property string|null $foto
 * @property string $tanggal
 * @property string|null $waktu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu query()
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu wherePersonilJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu wherePersonilKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu wherePersonilSat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu wherePoldaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereSampahAnorganikJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereSampahAnorganikKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereSampahOrganikJml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereSampahOrganikKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|report_sapu whereWaktu($value)
 */
	class report_sapu extends \Eloquent {}
}

