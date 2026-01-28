<?php
/*
Path: interface_adapters/gateway/dashboard_repository_interface.php
*/

interface DashboardRepositoryInterfaceV2 {
    public function getDashboardData($periodo, $label, $ref_time = null);
}
