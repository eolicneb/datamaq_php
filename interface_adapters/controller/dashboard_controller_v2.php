<?php
// Controller para la versión v1 del dashboard
require_once __DIR__ . '/../../use_cases/get_dashboard_data_v2.php';
require_once __DIR__ . '/../presenter/dashboard_presenter_v1.php';
require_once __DIR__ . '/../gateway/dashboard_repository_v2.php';
require_once __DIR__ . '/../gateway/dashboard_repository_interface_v2.php';

class DashboardControllerV2 {
    protected $repository;

    public function __construct() {
        $this->repository = new DashboardRepositoryV2();
    }

    public function getDashboardData($fecha, $turno) {
        $useCase = new GetDashboardDataV2($this->repository);
        $dashboard = $useCase->execute($fecha, $turno);
        $presenter = new DashboardPresenterV1();
        return $presenter->present($dashboard);
    }
}
