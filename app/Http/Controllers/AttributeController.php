<?php

namespace App\Http\Controllers;

use App\Services\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    // ================= سمات ====================

    // عرض جميع السمات مع القيم
    public function index()
    {
        $attributes = $this->attributeService->getAllAttributes();
        return response()->json(['success' => true, 'data' => $attributes]);
    }

    // إنشاء سمة (فقط للإدمن)
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'is_filterable' => 'nullable|boolean',
        ]);

        $attribute = $this->attributeService->createAttribute($request->all());
        return response()->json(['success' => true, 'data' => $attribute]);
    }

    // تعديل سمة (فقط للإدمن)
    public function update($id, Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name_en' => 'sometimes|string|max:255',
            'name_ar' => 'sometimes|string|max:255',
            'is_filterable' => 'nullable|boolean',
        ]);

        $attribute = $this->attributeService->updateAttribute($id, $request->all());
        return response()->json(['success' => true, 'data' => $attribute]);
    }

    // حذف سمة (فقط للإدمن)
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $this->attributeService->deleteAttribute($id);
        return response()->json(['success' => true, 'message' => 'Attribute deleted successfully']);
    }

    // ================= قيم ====================

    // إضافة قيمة لسمة (بائع أو إدمن)
    public function addValue(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value_en' => 'required|string|max:255',
            'value_ar' => 'required|string|max:255',
        ]);

        $value = $this->attributeService->addAttributeValue($request->all());
        return response()->json(['success' => true, 'data' => $value]);
    }

    // الموافقة على قيمة (فقط إدمن)
    public function approveValue($id)
    {
        $this->authorizeAdmin();

        $value = $this->attributeService->approveAttributeValue($id);
        return response()->json(['success' => true, 'data' => $value]);
    }

    // حذف قيمة
    public function deleteValue($id)
    {
        $this->authorizeAdmin();

        $this->attributeService->deleteAttributeValue($id);
        return response()->json(['success' => true, 'message' => 'Value deleted successfully']);
    }

    // عرض القيم المعلقة (لـ الإدمن)
    public function pendingValues()
    {
        $this->authorizeAdmin();

        $pending = $this->attributeService->getPendingValues();
        return response()->json(['success' => true, 'data' => $pending]);
    }

    // عرض القيم الموافق عليها لسمة معينة
    public function approvedValues($attributeId)
    {
        $values = $this->attributeService->getApprovedValuesByAttribute($attributeId);
        return response()->json(['success' => true, 'data' => $values]);
    }

    // ========== حماية =================
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only admin is allowed');
        }
    }
}
