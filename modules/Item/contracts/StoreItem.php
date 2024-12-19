<?php

namespace Modules\Item\Contracts;

class StoreItem
{

    public string $name;
    public int $category_id;
    public float $price;
    public string $description;
    public string|null $item_condition;
    public string|null $item_type;
    public bool $is_published;
    public string $image;
    public string $owner_name;
    public string|null $contact_number;
    public string|null $address;
    public string $latitude;
    public string $longitude;

    public function __construct($arrayData)
    {
        $this->name = $arrayData["name"];
        $this->category_id = $arrayData["category_id"];
        $this->price = $arrayData["price"];
        $this->description = $arrayData["description"];
        $this->item_condition = $arrayData["item_condition"];
        $this->item_type = $arrayData["item_type"];
        $this->is_published = $arrayData["is_published"];
        $this->image = $arrayData["image"];
        $this->owner_name = $arrayData["owner_name"];
        $this->contact_number = $arrayData["contact_number"];
        $this->address = $arrayData["address"];
        $this->latitude = $arrayData["location"]['latitude'];
        $this->longitude = $arrayData['location']['longitude'];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
            'item_condition' => $this->item_condition,
            'item_type' => $this->item_type,
            'is_published' => $this->is_published,
            'image' => $this->image,
            'owner_name' => $this->owner_name,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
