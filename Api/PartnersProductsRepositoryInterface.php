<?php

namespace Manugentoo\PartnerPortal\Api;

use Manugentoo\PartnerPortal\Api\Data\PartnersProductsInterface;

/**
 * Interface PartnersProductRepositoryInterface
 * @package Manugentoo\PartnerPortal\Api
 */
interface PartnersProductsRepositoryInterface
{
	/**
	 * @param PartnersProductsInterface $partner
	 * @return mixed
	 */
	public function save(PartnersProductsInterface $partner);

	/**
	 * @param $id
	 * @return PartnersProductsInterface
	 */
	public function getById($id);

	/**
	 * @param PartnersProductsInterface $partner
	 * @return mixed
	 */
	public function delete(PartnersProductsInterface $partner);

	/**
	 * @param $id
	 * @return mixed
	 */
	public function deleteById($id);
}