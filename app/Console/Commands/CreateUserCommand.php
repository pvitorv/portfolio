<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create';
    protected $description = 'Cria o usuário inicial do portfolio (Paulo Vitor).';

    public function handle(): int
    {
        if (User::where('email', 'pvitorv@gmail.com')->exists()) {
            $this->warn('Usuário pvitorv@gmail.com já existe.');
            return 0;
        }

        User::create([
            'name' => 'Paulo Vitor',
            'email' => 'pvitorv@gmail.com',
            'password' => bcrypt('C@pital1985790'),
        ]);

        $this->info('Usuário criado. Acesse /login com pvitorv@gmail.com e a senha definida.');
        return 0;
    }
}
