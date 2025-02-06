<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckUserActivity extends Command
{
    protected $signature = 'github-activity {username}';
    protected $description = 'Obtiene la actividad reciente de un usuario en GitHub';
    
    public function handle()
    {
        $username = $this->argument('username');

        $response = Http::get("https://api.github.com/users/{$username}/events");

        if ($response->successful()) {
            $events = $response->json();

            if (empty($events)) {
                $this->info("No hay actividad reciente para el usuario: $username");
                return;
            }

            foreach (array_slice($events, 0, 5) as $event) {
                $this->info("Evento: " . $event['type'] . " en " . $event['repo']['name']);
            }
        } else {
            $this->error("No se pudo obtener la actividad de GitHub para: $username");
        }
    }
}
