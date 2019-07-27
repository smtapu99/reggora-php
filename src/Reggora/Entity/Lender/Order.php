<?php
namespace Reggora\Entity\Lender;

use Reggora\Lender;
use Reggora\Entity\AbstractEntity;

use Illuminate\Support\Collection;

final class Order extends AbstractEntity {

	protected $accepted_vendor;

	protected $requested_vendors;

	public function __construct(array $data)
	{
		parent::__construct($data);

		if(isset($data['accepted_vendor']) && is_array($data['accepted_vendor']))
		{
			$this->accepted_vendor = new Vendor($data['accepted_vendor']);
		}

		if(isset($data['requested_vendors']) && is_array($data['requested_vendors']))
		{
			$requested_vendors = [];
			foreach($data['requested_vendors'] as $vendor)
			{
				$requested_vendors[] = new Vendor($vendor);
			}

			$this->requested_vendors = new Collection($requested_vendors);
		}

		if(isset($data['products']) && is_array($data['products']))
		{
			$products = [];
			foreach($data['products'] as $product)
			{
				$products[] = new Product($product);
			}

			$this->products = new Collection($products);
		}

		//todo: `loan_files` ?
	}

	public static function find(string $id)
	{
		return new Order(Lender::getInstance()->getAdapter()->get(sprintf('lender/order/%s', $id)));
	}

	public static function create(array $parameters)
	{
		$order = Lender::getInstance()->getAdapter()->post('lender/order/create', $parameters);
		return self::find($order);
	}

	public function delete()
	{
		return Lender::getInstance()->getAdapter()->delete(sprintf('lender/order/%s', $this->id));
	}

	public function save()
	{
		Lender::getInstance()->getAdapter()->put(sprintf('lender/order/%s', $id), $this->dirtyData);
		$this->clean();
	}

	public function getIdentifier()
	{
		return 'id';
	}
}