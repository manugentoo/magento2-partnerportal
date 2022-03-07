<?php

namespace Manugentoo\PartnerPortal\Api;

use Manugentoo\PartnerPortal\Api\Data\PartnersInterface;

/**
 * Interface PartnerRepositoryInterface
 * @package Manugentoo\PartnerPortal\Api
 */
interface PartnersRepositoryInterface
{
	/**
	 * @param PartnersInterface $partner
	 * @return mixed
	 */
	public function save(PartnersInterface $partner);

	/**
	 * @param $id
	 * @return PartnersInterfaces
	 */
	public function getById($id);

	/**
	 * @param PartnersInterface $partner
	 * @return mixed
	 */
	public function delete(PartnersInterface $partner);

	/**
	 * @param $id
	 * @return mixed
	 */
	public function deleteById($id);
}