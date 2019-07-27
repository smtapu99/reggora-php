<?php
namespace Reggora\Entity\Lender;

use Reggora\Lender;
use Reggora\Entity\AbstractEntity;

use Illuminate\Support\Collection;

final class Product extends AbstractEntity {

	public function all()
	{
		$products = Lender::getInstance()->getAdapter()->get('lender/product');
		foreach($products['products'] as $key => $data)
		{
			$products[$key] = new Product($data);
		}

		return new Collection($products);
	}

	public static function find(string $id)
	{
		return new Product(Lender::getInstance()->getAdapter()->get(sprintf('lender/product/%s', $id)));
	}

	public static function create(array $parameters)
	{
		$product = Lender::getInstance()->getAdapter()->post('lender/product/create', $parameters);
		return self::find($product);
	}

	public function delete()
	{
		return Lender::getInstance()->getAdapter()->delete(sprintf('lender/product/%s', $this->id));
	}

	public function save()
	{
		Lender::getInstance()->getAdapter()->put(sprintf('lender/product/%s', $id), $this->getDirtyData());
		$this->clean();
	}

	public function getIdentifier()
	{
		return 'id';
	}
}