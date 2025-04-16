<?php

namespace App\Http\Controllers;

use App\Services\EntityService;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    protected EntityService $service;

    public function __construct(EntityService $service)
    {
        $this->service = $service;
    }

    public function index($entityType)
    {
        $entities = $this->service->getAll($entityType);
        return response()->json($entities);
    }

    public function store(Request $request, $entityType)
    {
        $requestClass = $this->getRequestClass($entityType);
        $validatedRequest = app($requestClass);
        $data = $validatedRequest->validated();

        if ($entityType === 'recipes') {
            $data['ingredients'] = is_array($data['ingredients']) ? implode(', ', $data['ingredients']) : $data['ingredients'];
            $data['instructions'] = is_array($data['instructions']) ? implode('; ', $data['instructions']) : $data['instructions'];
        }
        if ($entityType === 'products') {
            $data['brand'] = $data['brand'] ?? 'Unknown';
        }

        $entity = $this->service->addEntityViaApi($entityType, $data);
        return response()->json($entity, 201);
    }

    public function fetchAndSave(Request $request, $entityType)
    {
        $query = $request->query('q');
        $items = $this->service->fetchFromApi($entityType, $query);
        $this->service->saveEntities($entityType, $items);
        return response()->json(['message' => ucfirst($entityType) . ' сохранение прошло успешно']);
    }

    protected function getRequestClass($entityType)
    {
        $requestClasses = [
            'products' => \App\Http\Requests\ProductStoreRequest::class,
            'recipes' => \App\Http\Requests\RecipeStoreRequest::class,
            'users' => \App\Http\Requests\UserStoreRequest::class,
            'posts' => \App\Http\Requests\PostStoreRequest::class,
        ];

        if (!isset($requestClasses[$entityType])) {
            throw new \Exception("Неизвестный тип сущности: $entityType");
        }

        return $requestClasses[$entityType];
    }
}
