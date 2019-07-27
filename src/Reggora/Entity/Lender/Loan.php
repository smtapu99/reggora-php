<?php
namespace Reggora\Entity\Lender;

use Reggora\Lender;
use Reggora\Entity\AbstractEntity;

use Illuminate\Support\Collection;

final class Loan extends AbstractEntity {

	public static function find(string $id)
	{
		$json = Lender::getInstance()->getAdapter()->get(sprintf('lender/loan/%s', $id));

		if(!empty($json) && isset($json['id']))
		{
			return new Loan($json);
		}
		
		return null;
	}

	public static function create(array $parameters)
	{
		$loan = Lender::getInstance()->getAdapter()->post('lender/loan/create', $parameters);
		return self::find($loan);
	}

	public function delete()
	{
		return Lender::getInstance()->getAdapter()->delete(sprintf('lender/loan/%s', $this->id));
	}

	public function save()
	{
		Lender::getInstance()->getAdapter()->put(sprintf('lender/loan/%s', $id), $this->getDirtyData());
		$this->clean();
	}

	public function all(int $offset = 0, int $limit = 0, string $ordering = '-created', $loanOfficer = null)
	{
		$loans = Lender::getInstance()->getAdapter()->get('lender/loans', [
			'offset' => $offset,
			'limit' => $limit,
			'ordering' => $ordering,
			'loan_officer' => $loanOfficer,
		]);

		foreach($loans['loans'] as $key => $data)
		{
			$loans[$key] = new Loan($data);
		}

		return new Collection($loans);
	}

	public function getIdentifier()
	{
		return 'id';
	}
}