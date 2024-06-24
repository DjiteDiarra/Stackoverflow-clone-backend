<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Question;
use App\Models\Answer;
use App\Policies\QuestionPolicy;
use App\Policies\AnswerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Question::class => QuestionPolicy::class,
        Answer::class => AnswerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir des gates pour vérifier les rôles
        Gate::define('validate-answer', function ($user) {
            return $user->is_supervisor;
        });

    }
}
