<?php namespace ProjectManagement\Abstracts;

interface RepositoryInterface {

	public function all ( $fields = [] );

	public function getById ( $id, array $with = [] );

	public function delete ( $id );
}
