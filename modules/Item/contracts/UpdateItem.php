<?php

namespace Modules\Item\Contracts;

class UpdateItem
{

    public string $name;
    public int $category_id;
    public float $price;
    public string $description;
    public string|null $item_condition;
    public string|null $item_type;
    public bool $is_published;
    public string|null $image;
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
        $this->is_published = $arrayData["is_published"] == 'true' ? true : false;
        $this->image = $arrayData["image"] ?? null;
        $this->owner_name = $arrayData["owner_name"];
        $this->contact_number = $arrayData["contact_number"];
        $this->address = $arrayData["address"];
        $this->latitude = $arrayData["location"]['latitude'];
        $this->longitude = $arrayData['location']['longitude'];
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'description' => $this->description,
            'item_condition' => $this->item_condition,
            'item_type' => $this->item_type,
            'is_published' => $this->is_published,
            'owner_name' => $this->owner_name,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
        if ($this->image) {
            $data['image'] = $this->image;
        }
        return $data;
    }
}
