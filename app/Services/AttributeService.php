<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;

class AttributeService
{
    // ========== السمات ==========
    public function createAttribute(array $data)
    {
        return Attribute::create([
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar'],
            'is_filterable' => $data['is_filterable'] ?? true,
        ]);
    }

    public function updateAttribute(int $id, array $data)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->update($data);
        return $attribute;
    }

    public function deleteAttribute(int $id): bool
    {
        $attribute = Attribute::findOrFail($id);
        return $attribute->delete();
    }

    public function getAllAttributes()
    {
        return Attribute::with('values')->get();
    }

    public function getAttributeById(int $id)
    {
        return Attribute::with('values')->findOrFail($id);
    }

    // ========== القيم ==========
    public function addAttributeValue(array $data): AttributeValue
    {
        $user = Auth::user();

        $status = $user->role === 'admin' ? 'approved' : 'pending';
        $suggestedBy = $user->role === 'seller' ? $user->id : null;

        return AttributeValue::create([
            'attribute_id'   => $data['attribute_id'],
            'value_en'       => $data['value_en'],
            'value_ar'       => $data['value_ar'],
            'status'         => $status,
            'suggested_by'   => $suggestedBy,
        ]);
    }

    public function approveAttributeValue(int $id): AttributeValue
    {
        $value = AttributeValue::findOrFail($id);
        $value->status = 'approved';
        $value->save();

        return $value;
    }

    public function deleteAttributeValue(int $id): bool
    {
        return AttributeValue::findOrFail($id)->delete();
    }

    public function getPendingValues()
    {
        return AttributeValue::where('status', 'pending')->with('attribute')->get();
    }

    public function getApprovedValuesByAttribute(int $attributeId)
    {
        return AttributeValue::where('attribute_id', $attributeId)
            ->where('status', 'approved')
            ->get();
    }
}
