<?php
namespace CSY2028;

interface Routes
{
	public function getRoutes(): array;
	public function getLayoutVars($title, $output): array;
	public function getAuthentication(): \CSY2028\Authentication;
	public function checkPermission($permission): bool;
}