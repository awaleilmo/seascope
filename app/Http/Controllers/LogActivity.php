<?php

namespace App\Http\Controllers;

use App\Models\foto_klinik_log;
use App\Models\foto_perpustakaan_log;
use App\Models\foto_rw_log;
use App\Models\foto_sambang_log;
use App\Models\foto_sapu_log;
use App\Models\Gakkum_log;
use App\Models\Globals_log;
use App\Models\kia_log;
use App\Models\lokasi_log;
use App\Models\polda_log;
use App\Models\report_klinik_log;
use App\Models\report_perpustakaan_log;
use App\Models\report_rw_log;
use App\Models\report_sambang_log;
use App\Models\report_sapu_log;
use App\Models\Role_log;
use App\Models\TargetPU_log;
use App\Models\User_log;
use Request;

class LogActivity extends Controller
{
    public function logUser($subject, $id_log, $action): bool
    {
        return user_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logRole($subject, $id_log, $action): bool
    {
        return role_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logLokasi($subject, $id_log, $action): bool
    {
        return lokasi_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logPolda($subject, $id_log, $action): bool
    {
        return polda_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logTarget($subject, $id_log, $action): bool
    {
        return TargetPU_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logSambang($subject, $id_log, $action): bool
    {
        return report_sambang_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logSapu($subject, $id_log, $action): bool
    {
        return report_sapu_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logPerpustakaan($subject, $id_log, $action): bool
    {
        return report_perpustakaan_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logKlinik($subject, $id_log, $action): bool
    {
        return report_klinik_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logRw($subject, $id_log, $action): bool
    {
        return report_rw_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logFotoKlinik($subject, $id_log, $action): bool
    {
        return foto_klinik_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logFotoPerpustakaan($subject, $id_log, $action): bool
    {
        return foto_perpustakaan_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logFotoRw($subject, $id_log, $action): bool
    {
        return foto_rw_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logFotoSambang($subject, $id_log, $action): bool
    {
        return foto_sambang_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logFotoSapu($subject, $id_log, $action): bool
    {
        return foto_sapu_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logGakkum($subject, $id_log, $action): bool
    {
        return Gakkum_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logKia($subject, $id_log, $action): bool
    {
        return kia_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function logKGlobal($subject, $id_log, $action): bool
    {
        return Globals_log::query()->insert([
            'user_id' => auth()->check() ? auth()->user()['id'] : 1,
            'id_log' => $id_log,
            'subject' => $subject,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip' => Request::ip(),
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
