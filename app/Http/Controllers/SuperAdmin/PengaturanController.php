<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PengaturanController extends Controller
{
    public function editStorage()
    {
        $total = disk_total_space("/");
        $free = disk_free_space("/");
        $used = $total - $free;

        $totalGB = round($total / 1073741824, 2);
        $usedGB = round($used / 1073741824, 2);

        $percentage = round(($usedGB / $totalGB) * 100);

        return Inertia::render('SuperAdmin/Pengaturan/EditStorage', [
            'storageData' => [
                'terpakai' => $usedGB . ' GB',
                'limitSaatIni' => $totalGB . ' GB',
                'penggunaan' => $percentage . '%',
            ]
        ]);
    }
}