<?php

namespace App\Console\Commands;

use App\Domain\Contracts\FaculdadeClientInterface;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        protected FaculdadeClientInterface $faculdade
    )
    {
        return parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->faculdade->signInAuthenticated();
        $graduation = $this->faculdade->getInfoListGraduation();
        $materials = $this->faculdade->courseMaterials($graduation[0]->idUsuarioCurso,$graduation[0]->idCurso);
        $this->faculdade->listStudentActivities($materials[2]['id'],$materials[2]['idSalaVirtualOfertaAproveitamento']);
    }
}
