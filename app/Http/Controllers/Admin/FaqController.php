<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function listfaq()
    {
        $faqs = Faq::orderBy('id', 'desc')->get();
        return view('admin.faq.list', compact('faqs'));
    }
    public function addfaq()
    {
        return view('admin.faq.add');
    }

    public function storefaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['success' => true, 'message' => 'FAQ added successfully!']);
    }


    public function editfaq($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faq.edit', compact('faq'));
    }

    public function updatefaq(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $faq = Faq::findOrFail($id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['success' => true, 'message' => 'FAQ updated successfully!']);
    }

    public function deletefaq($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('FAQ deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ.'
            ], 500);
        }
    }


    public function faqOnStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'faqId' => 'required|exists:faqs,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $faq = Faq::findOrFail($request->faqId);
            $faq->is_active = $request->status;
            $faq->save();

            $statusMessages = [
                0 => 'inactive',
                1 => 'active',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Faq status updated successfully',
                'data' => [
                    'is_active' => $faq->is_active,
                    'status_text' => $statusMessages[$faq->is_active] ?? 'unknown'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Faq status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Faq status: ' . $e->getMessage()
            ], 500);
        }
    }
}
