<?php

namespace App\Console\Commands;

use App\Http\Service\Parsers\KartotekaParser;
use Illuminate\Console\Command;

class Kartoteka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kartoteka';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $start;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->start = new KartotekaParser();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->start->getData();
    }
}
