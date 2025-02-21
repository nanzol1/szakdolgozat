<?php
namespace App\Services;

use App\Models\Admin\Log;


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

    public function logUserAction(string $action, string $description = null,$user_id = null)
    {
        return Log::create([
            'action' => $action,
            'description' => $description,
            'admin_id' => $user_id,
        ]);
    }

    public function logCompanyAction(string $action, string $description = null,$company_id = null)
    {
        return Log::create([
            'action' => $action,
            'description' => $description,
            'admin_id' => $company_id,
        ]);
    }
}


?>