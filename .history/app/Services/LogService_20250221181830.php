<?php
namespace App\Services;

use App\Models\Log;

class LogService
{
    public function logAction(string $action, string $description = null)
    {
        return Log::create([
            'action' => $action,
            'description' => $description,
        ]);
    }
}


?>