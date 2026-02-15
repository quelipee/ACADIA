<?php

namespace App\Console\Commands;

use App\Application\Services\ResolverAssessmentService;
use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\Enums\AiProvider;
use App\Domain\Enums\ExamActivityType;
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
        protected FaculdadeClientInterface $faculdade,
        protected GraduationClientInterface $graduation,
        protected DisciplinaClientInterface $subject,
        protected ResolverAssessmentService $service,
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
        $graduation = $this->graduation->getInfoListGraduation();
        $disciplina  = $this->subject->courseDiscipline($graduation[0]->idUsuarioCurso,$graduation[0]->idCurso);
        $this->service->resolver($disciplina, ExamActivityType::APOL, AiProvider::GEMINI);
    }
}
