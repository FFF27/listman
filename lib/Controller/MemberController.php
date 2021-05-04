<?php

namespace OCA\Listman\Controller;

use OCA\Listman\AppInfo\Application;
use OCA\Listman\Service\ListmanService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

class MemberController extends Controller {
	/** @var ListmanService */
	private $service;
	private $userId;

	use Errors;

	public function __construct(IRequest $request,
								ListmanService $service,
								string $userId) {
		parent::__construct(Application::APP_ID, $request);
		$this->service = $service;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 */
	public function index(): DataResponse {
		return new DataResponse($this->service->findAllMembers($this->userId));
	}

	/**
	 * @NoAdminRequired
	 */
	public function show(int $id): DataResponse {
		return $this->handleNotFound(function () use ($id) {
			return $this->service->findMember($id, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 */
	public function create(string $email, string $name, integer $state): DataResponse {
		return new DataResponse($this->service->createMember($email, $name, $state, $this->userId));
	}

	/**
	 * @NoAdminRequired
	 */
	public function update(int $id, string $email, string $name, integer $state): DataResponse {
		return $this->handleNotFound(function () use ($id, $email, $name, $state) {
			return $this->service->updateMember($id, $email, $name, $state, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 */
	public function destroy(int $id): DataResponse {
		return $this->handleNotFound(function () use ($id) {
			return $this->service->deleteMember($id, $this->userId);
		});
	}
}
