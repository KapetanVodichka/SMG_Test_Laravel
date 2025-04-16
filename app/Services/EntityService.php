<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EntityService
{
    protected Client $client;

    protected array $entityConfig = [
        'products' => [
            'model' => \App\Models\Product::class,
            'endpoint' => 'https://dummyjson.com/products',
            'add_endpoint' => 'https://dummyjson.com/products/add',
            'api_key' => 'products',
        ],
        'recipes' => [
            'model' => \App\Models\Recipe::class,
            'endpoint' => 'https://dummyjson.com/recipes',
            'add_endpoint' => 'https://dummyjson.com/recipes/add',
            'api_key' => 'recipes',
        ],
        'users' => [
            'model' => \App\Models\User::class,
            'endpoint' => 'https://dummyjson.com/users',
            'add_endpoint' => 'https://dummyjson.com/users/add',
            'api_key' => 'users',
        ],
        'posts' => [
            'model' => \App\Models\Post::class,
            'endpoint' => 'https://dummyjson.com/posts',
            'add_endpoint' => 'https://dummyjson.com/posts/add',
            'api_key' => 'posts',
        ],
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll($entityType)
    {
        $model = $this->getModel($entityType);
        return $model::all();
    }

    public function fetchFromApi($entityType, $query = null)
    {
        $endpoint = $this->getEndpoint($entityType);
        $url = $query ? $endpoint . '/search?q=' . urlencode($query) : $endpoint;
        $response = $this->client->get($url, ['verify' => false]);
        $data = json_decode($response->getBody(), true);
        $apiKey = $this->entityConfig[$entityType]['api_key'];
        return $data[$apiKey] ?? [];
    }

    public function saveEntities($entityType, array $data)
    {
        $model = $this->getModel($entityType);
        foreach ($data as $item) {
            if ($entityType === 'recipes') {
                $item['ingredients'] = is_array($item['ingredients']) ? implode(', ', $item['ingredients']) : $item['ingredients'];
                $item['instructions'] = is_array($item['instructions']) ? implode('; ', $item['instructions']) : $item['instructions'];
            }
            if ($entityType === 'products') {
                $item['brand'] = $item['brand'] ?? 'Unknown';
            }
            $model::create($item);
        }
    }

    public function addEntityViaApi($entityType, array $data)
    {
        $addEndpoint = $this->getAddEndpoint($entityType);
        $response = $this->client->post($addEndpoint, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
            'verify' => false,
        ]);
        return json_decode($response->getBody(), true);
    }

    protected function getModel($entityType)
    {
        if (!isset($this->entityConfig[$entityType])) {
            throw new \Exception("Неизвестный тип сущности: $entityType");
        }
        return $this->entityConfig[$entityType]['model'];
    }

    protected function getEndpoint($entityType)
    {
        if (!isset($this->entityConfig[$entityType])) {
            throw new \Exception("Неизвестный тип сущности: $entityType");
        }
        return $this->entityConfig[$entityType]['endpoint'];
    }

    protected function getAddEndpoint($entityType)
    {
        if (!isset($this->entityConfig[$entityType]['add_endpoint'])) {
            throw new \Exception("Неизвестный тип сущности: $entityType");
        }
        return $this->entityConfig[$entityType]['add_endpoint'];
    }
}
