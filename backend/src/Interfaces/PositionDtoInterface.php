<?php 
namespace App\Interfaces;

interface PositionDtoInterface {
    public function getName(): string;
    public function getDescription(): string;
    public function getAttributes(): array;
}