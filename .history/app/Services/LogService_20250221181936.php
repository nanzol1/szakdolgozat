<?php
namespace App\Services;

use App\Admin\Models\Log;


class LogService
{
    public function logAction(string $action, string $description = null,$admin_id = null)
    {
        return Log::create([
            'action' => $action,
            'description' => $description,
            'admin_id' => $admin_id,
        ]);
    }
}


?>