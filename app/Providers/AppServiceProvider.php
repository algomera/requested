<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	    Model::unguard();

	    Collection::macro('paginate', function($perPage, $page = null, $pageName = 'page') {
		    $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
		    return new LengthAwarePaginator(
			    $this->forPage($page, $perPage), // $items
			    $this->count(),                  // $total
			    $perPage,
			    $page,
			    [                                // $options
				    'path' => LengthAwarePaginator::resolveCurrentPath(),
				    'pageName' => $pageName,
			    ]
		    );
	    });
    }
}
