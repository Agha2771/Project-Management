<?php namespace ProjectManagement;

use Illuminate\Support\ServiceProvider;
use ProjectManagement\Repositories\User\UserEloquentRepository;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use ProjectManagement\Repositories\Role\RoleEloquentRepository;
use ProjectManagement\Repositories\Role\RoleRepositoryInterface;
use ProjectManagement\Repositories\Client\ClientEloquentRepository;
use ProjectManagement\Repositories\Client\ClientRepositoryInterface;
use ProjectManagement\Repositories\ClientNotes\ClientNotesRepositoryInterface;
use ProjectManagement\Repositories\ClientNotes\ClientNotesEloquentRepository;
use ProjectManagement\Repositories\Project\ProjectRepositoryInterface;
use ProjectManagement\Repositories\Project\ProjectEloquentRepository;
use ProjectManagement\Repositories\Inquiry\InquiryRepositoryInterface;
use ProjectManagement\Repositories\Inquiry\InquiryEloquentRepository;
use ProjectManagement\Repositories\Invoice\InvoiceRepositoryInterface;
use ProjectManagement\Repositories\Invoice\InvoiceEloquentRepository;
use ProjectManagement\Repositories\ProjectAssignees\ProjectAssigneesRepositoryInterface;
use ProjectManagement\Repositories\ProjectAssignees\ProjectAssigneesEloquentRepository;
use ProjectManagement\Repositories\Account\AccountRepositoryInterface;
use ProjectManagement\Repositories\Account\AccountEloquentRepository;
use ProjectManagement\Repositories\Payment\PaymentRepositoryInterface;
use ProjectManagement\Repositories\Payment\PaymentEloquentRepository;
use ProjectManagement\Repositories\ProjectExpense\ProjectExpenseRepositoryInterface;
use ProjectManagement\Repositories\ProjectExpense\ProjectExpenseEloquentRepository;
use ProjectManagement\Repositories\Country\CountryRepositoryInterface;
use ProjectManagement\Repositories\Country\CountryEloquentRepository;
use ProjectManagement\Repositories\State\StateRepositoryInterface;
use ProjectManagement\Repositories\State\StateEloquentRepository;
use ProjectManagement\Repositories\City\CityRepositoryInterface;
use ProjectManagement\Repositories\City\CityEloquentRepository;
use ProjectManagement\Repositories\SubCategory\SubCategoryRepositoryInterface;
use ProjectManagement\Repositories\SubCategory\SubCategoryEloquentRepository;
use ProjectManagement\Repositories\Category\CategoryRepositoryInterface;
use ProjectManagement\Repositories\Category\CategoryEloquentRepository;
use ProjectManagement\Repositories\Expense\ExpenseRepositoryInterface;
use ProjectManagement\Repositories\Expense\ExpenseEloquentRepository;
use ProjectManagement\Repositories\Task\TaskRepositoryInterface;
use ProjectManagement\Repositories\Task\TaskEloquentRepository;
use ProjectManagement\Repositories\TaskAssignees\TaskAssigneesRepositoryInterface;
use ProjectManagement\Repositories\TaskAssignees\TaskAssigneesEloquentRepository;

class RepositoryServiceProvider extends ServiceProvider {

	public function register () {

		$bindings = [
			[ UserRepositoryInterface::class, UserEloquentRepository::class ],
			[ RoleRepositoryInterface::class, RoleEloquentRepository::class ],
			[ ClientRepositoryInterface::class, ClientEloquentRepository::class ],
			[ ClientNotesRepositoryInterface::class, ClientNotesEloquentRepository::class ],
			[ ProjectRepositoryInterface::class, ProjectEloquentRepository::class ],
			[ InquiryRepositoryInterface::class, InquiryEloquentRepository::class ],
			[ InvoiceRepositoryInterface::class, InvoiceEloquentRepository::class ],
			[ ProjectAssigneesRepositoryInterface::class, ProjectAssigneesEloquentRepository::class ],
			[ AccountRepositoryInterface::class, AccountEloquentRepository::class ],
			[ PaymentRepositoryInterface::class, PaymentEloquentRepository::class ],
			[ ProjectExpenseRepositoryInterface::class, ProjectExpenseEloquentRepository::class ],
			[ CountryRepositoryInterface::class, CountryEloquentRepository::class ],
			[ StateRepositoryInterface::class, StateEloquentRepository::class ],
			[ CityRepositoryInterface::class, CityEloquentRepository::class ],
			[ CategoryRepositoryInterface::class, CategoryEloquentRepository::class ],
			[ SubCategoryRepositoryInterface::class, SubCategoryEloquentRepository::class ],
			[ ExpenseRepositoryInterface::class, ExpenseEloquentRepository::class ],
			[ TaskRepositoryInterface::class, TaskEloquentRepository::class ],
			[ TaskAssigneesRepositoryInterface::class, TaskAssigneesEloquentRepository::class ],
		];
		$this->bindInterfacesWithTheirImplementations( $bindings );
	}

	public function bindInterfacesWithTheirImplementations ( $bindings ) {
		foreach ( $bindings as $binding ) {

		    $this->app->bind( $binding[ 0 ], $binding[ 1 ] );
		}

	}
}
